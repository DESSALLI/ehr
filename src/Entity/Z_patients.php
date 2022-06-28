<?php

namespace App\Entity;

use App\Repository\PatientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Z_patients
{
    
    private $id;

    private $code;


    private $authaurisation;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }



    

    /**
     * Get the value of authaurisation
     */ 
    public function getAuthaurisation()
    {
        return $this->authaurisation;
    }

    /**
     * Set the value of authaurisation
     *
     * @return  self
     */ 
    public function setAuthaurisation($authaurisation)
    {
        $this->authaurisation = $authaurisation;

        return $this;
    }
}
