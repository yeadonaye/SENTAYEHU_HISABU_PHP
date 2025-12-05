<?php
    class Commentaire {
        private int $idCommentaire;
        private string $description;
        private string $date;
        private int $idJoueur;

        public function __construct(int $idCommentaire, string $description, string $date, int $idJoueur) {
            $this->idCommentaire = $idCommentaire;
            $this->description = $description;
            $this->date = $date;
            $this->idJoueur = $idJoueur;
        }

        //Getters
        public function getIdCommentaire(): int{
            return $this->idCommentaire;
        }

        public function getDescription(): string{
            return $this->description;
        }

        public function getDate(): string{
            return $this->date;
        }

        public function getIdJoueur(): int{
            return $this->idJoueur;
        }

        //Setters
        public function setDescription(string $description): void{
            $this->description = $description;
        }

        public function setDate(string $date): void{
            $this->date = $date;
        }

        public function setIdJoueur(int $idJoueur): void{
            $this->idJoueur = $idJoueur;
        }
    }
?>