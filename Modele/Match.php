<?php
    class Match_ {
        //Definition des variables
        private int $idMatch;
        private string $dateRencontre;
        private string $heure;
        private string $nomEquipeAdverse;
        private string $lieu;
        private int $score_adversaire;
        private int $score_nous;

        //Definition du contructeur
        public function __construct(int $idMatch, string $dateRencontre, string $heure, string $nomEquipeAdverse, string $lieu, int $score_adversaire, int $score_nous) {
            $this->idMatch = $idMatch;
            $this->dateRencontre = $dateRencontre;
            $this->heure = $heure;
            $this->nomEquipeAdverse = $nomEquipeAdverse;
            $this->lieu = $lieu;
            $this->score_nous = $score_nous;
            $this->score_adversaire = $score_adversaire;
        }

        //Getters
        public function getIdMatch(): int{
            return $this->idMatch;
        }

        public function getDateRencontre(): string{
            return $this->dateRencontre;
        }

        public function getHeure(): string{
            return $this->heure;
        }

        public function getNomEquipeAdverse(): string{
            return $this->nomEquipeAdverse;
        }

        public function getLieu(): string{
            return $this->lieu;
        }

        public function getScoreAdversaire(): int{
            return $this->score_adversaire;
        }

        public function getScoreNous(): int{
            return $this->score_nous;
        }

        //Setters
        public function setDateRencontre(string $date): void{
            $this->dateRencontre = $date;
        }

        public function setHeure(string $time): void{
            $this->heure = $time;
        }

        public function setNomEquipeAdverse(string $nom): void{
            $this->nomEquipeAdverse = $nom;
        }

        public function setLieu(string $lieu): void{
            $this->lieu = $lieu;
        }

        public function setScoreAdversaire(int $score_adversaire): void{
            $this->score_adversaire = $score_adversaire;
        }

        public function setScoreNous(int $score_nous): void{
            $this->score_nous = $score_nous;
        }
    }
?>