<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Fecha = null;

    #[ORM\Column(length: 50)]
    private ?string $Usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $Accion = null;

    #[ORM\Column(length: 255)]
    private ?string $Archivo = null;

    #[ORM\Column(length: 255)]
    private ?string $Compartido = null;

    #[ORM\Column(length: 255)]
    private ?string $Path = null;

    #[ORM\Column(length: 255)]
    private ?string $IPCliente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->Fecha;
    }

    public function setFecha(\DateTimeInterface $Fecha): static
    {
        $this->Fecha = $Fecha;

        return $this;
    }

    public function getUsuario(): ?string
    {
        return $this->Usuario;
    }

    public function setUsuario(string $Usuario): static
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getAccion(): ?string
    {
        return $this->Accion;
    }

    public function setAccion(string $Accion): static
    {
        $this->Accion = $Accion;

        return $this;
    }

    public function getArchivo(): ?string
    {
        return $this->Archivo;
    }

    public function setArchivo(string $Archivo): static
    {
        $this->Archivo = $Archivo;

        return $this;
    }

    public function getCompartido(): ?string
    {
        return $this->Compartido;
    }

    public function setCompartido(string $Compartido): static
    {
        $this->Compartido = $Compartido;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->Path;
    }

    public function setPath(string $Path): static
    {
        $this->Path = $Path;

        return $this;
    }

    public function getIPCliente(): ?string
    {
        return $this->IPCliente;
    }

    public function setIPCliente(string $IPCliente): static
    {
        $this->IPCliente = $IPCliente;

        return $this;
    }
}
