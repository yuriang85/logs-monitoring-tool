<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

#[AsCommand(
    name: 'app:ImportLogs',
    description: 'Importar registros logs',
)]
class ImportLogsCommand extends Command
{
    protected static $defaultName = 'app:import-logs';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importa logs desde un archivo CSV a la base de datos.')
            ->setHelp('Este comando importa registros de acceso desde un archivo CSV generado por un servidor Windows al sistema Symfony.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvPath = '/var/www/logs-monitoring-tool/logs'; // Ruta donde se almacenan los CSV
        $latestCsv = $this->getLatestCsvFile($csvPath);

        if (!$latestCsv) {
            $io->error('No se encontró ningún archivo CSV en el directorio.');
            return Command::FAILURE;
        }

        $io->info("Procesando archivo: $latestCsv");

        $file = fopen($latestCsv, 'r');

        if (!$file) {
            throw new FileNotFoundException("No se pudo abrir el archivo: $latestCsv");
        }

        // Leer y contar las líneas del archivo para inicializar la barra de progreso
        $totalLines = count(file($latestCsv)) - 1; // Restar 1 para excluir la cabecera
        $progressBar = new ProgressBar($output, $totalLines);
        $progressBar->setFormat('%current%/%max% [%bar%] %percent:3s%% | Tiempo: %elapsed:6s% | Estimado: %estimated:-6s%');
        $progressBar->setBarCharacter('<fg=green>=</>');
        $progressBar->setEmptyBarCharacter('<fg=red>-</>');
        $progressBar->setProgressCharacter('<fg=yellow>></>');
        $progressBar->start();

        // Ignorar la cabecera del archivo
        fgetcsv($file);

        $imported = 0;

        while (($data = fgetcsv($file)) !== false) {
            // Ignorar líneas vacías
            if (empty(array_filter($data))) {
                continue;
            }

            // Verificar si la línea tiene 7 columnas
            if (count($data) !== 7) {
                $io->warning("Formato inválido en la línea: " . implode(", ", $data) . ". Saltando línea.");
                continue;
            }

            [$fecha, $usuario, $accion, $archivo, $compartido, $path, $ipCliente] = $data;

            // Crear una nueva instancia de Log y guardar los datos
            $log = new Log();
            $log->setFecha(new \DateTime($fecha));
            $log->setUsuario($usuario);
            $log->setAccion($accion);
            $log->setArchivo($archivo);
            $log->setCompartido($compartido);
            $log->setPath($path);
            $log->setIpCliente($ipCliente);

            $this->entityManager->persist($log);
            $imported++;

            // Avanzar la barra de progreso
            $progressBar->advance();
        }

        fclose($file);

        // Guardar los cambios en la base de datos
        $this->entityManager->flush();

        $progressBar->finish();
        $io->newLine();

        $io->success("$imported registros importados correctamente.");
        return Command::SUCCESS;
    }

    /**
     * Obtiene el archivo CSV más reciente del directorio especificado.
     */
    private function getLatestCsvFile(string $directory): ?string
    {
        $files = glob("$directory/AuditLogs_*.csv");
        if (empty($files)) {
            return null;
        }

        return array_reduce($files, function ($latest, $file) {
            return filemtime($file) > filemtime($latest) ? $file : $latest;
        }, $files[0]);
    }
}
