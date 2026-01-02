<?php
    interface ModeleDao {
        public function selectAll(): array;
        public function selectById(int $id):object;
        public function insert(object $obj):bool;
        public function update(object $obj):bool;
        public function delete(int $id):bool;
    }
?>