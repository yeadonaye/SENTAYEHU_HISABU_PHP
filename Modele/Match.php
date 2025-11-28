<?php
    class Match_ {
        //Definition des variables
        private int $id;
        private String $dateRencontre;
        private String $heure;
        private String $nomEquipeAdverse;
        private String $lieu;
        private String $resultat;

        //Definition du contructeur
        public function __construct(int $id, String $dateRencontre, String $heure, String $nomEquipeAdverse, String $lieu, String $resultat) {
            $this->id = $id;
            $this->dateRencontre = $dateRencontre;
            $this->heure = $heure;
            $this->nomEquipeAdverse = $nomEquipeAdverse;
            $this->lieu = $lieu;
            $this->resultat = $resultat;
        }

        //Getters
        public function getId(): int{
            return $this->id;
        }

        public function getDateRencontre(): String{
            return $this->dateRencontre;
        }

        public function getHeure(): String{
            return $this->heure;
        }

        public function getNomEquipeAdverse(): String{
            return $this->nomEquipeAdverse;
        }

        public function getLieu(): String{
            return $this->lieu;
        }

        public function getResultat(): String{
            return $this->resultat;
        }

        //Setters
        public function setDateRencontre(String $date): void{
            $this->dateRencontre = $date;
        }

        public function setHeure(String $time): void{
            $this->heure = $time;
        }

        public function setNomEquipeAdverse(String $nom): void{
            $this->nomEquipeAdverse = $nom;
        }

        public function setLieu(String $lieu): void{
            $this->lieu = $lieu;
        }

        public function setResultat(String $resultat): void{
            $this->resultat = $resultat;
        }
    }
?>