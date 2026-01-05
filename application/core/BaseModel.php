<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model
{
    protected $table = '';
    protected $primaryKey = 'id';
    protected $with = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function find($id)
    {
        $record = $this->db->where($this->primaryKey, $id)
                           ->get($this->table)
                           ->row();

        if ($record && !empty($this->with)) {
            $record = $this->loadRelations($record);
        }

        return $record;
    }

    public function all()
    {
        $results = $this->db->get($this->table)->result();

        if (!empty($this->with)) {
            foreach ($results as &$record) {
                $record = $this->loadRelations($record);
            }
        }

        return $results;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        $this->with = $relations;
        return $this;
    }

    public function hasMany($relatedModel, $foreignKey, $localKey, $value)
    {
        $this->load->model($relatedModel);
        $modelName = basename($relatedModel);
        $related = $this->$modelName;

        // Selalu gunakan query builder sebelum get()
        return $related->db->where($foreignKey, $value)
                           ->get($related->table)
                           ->result();
    }

    public function belongsTo($relatedModel, $foreignKey, $ownerKey, $value)
    {
        $this->load->model($relatedModel);
        $modelName = basename($relatedModel);
        $related = $this->$modelName;

        return $related->db->where($ownerKey, $value)
                           ->get($related->table)
                           ->row();
    }

    protected function loadRelations($record)
    {
        foreach ($this->with as $relation) {
            if (method_exists($this, $relation)) {
                $record->$relation = $this->$relation($record);
            }
        }
        return $record;
    }
}
