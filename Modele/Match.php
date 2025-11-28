<?php
    class Match_ {
        //Definition des variables
        private int $idMatch;
        private string $dateRencontre;
        private string $heure;
        private string $nomEquipeAdverse;
        private string $lieu;
        private string $resultat;

        //Definition du contructeur
        public function __construct(int $idMatch, string $dateRencontre, string $heure, string $nomEquipeAdverse, string $lieu, string $resultat) {
            $this->idMatch = $idMatch;
            $this->dateRencontre = $dateRencontre;
            $this->heure = $heure;
            $this->nomEquipeAdverse = $nomEquipeAdverse;
            $this->lieu = $lieu;
            $this->resultat = $resultat;
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

        public function getResultat(): string{
            return $this->resultat;
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

        public function setResultat(string $resultat): void{
            $this->resultat = $resultat;
        }
    }
?>