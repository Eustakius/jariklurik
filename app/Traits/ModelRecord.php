<?php

namespace App\Traits;

trait ModelRecord
{
    public function getEmptyRecord(bool $returnEntity = true)
    {
        $empty = [];

        foreach ($this->allowedFields as $field) {
            // Jika entity punya method setXxx() tapi method itu hanya lempar exception, skip
            if ($returnEntity && $this->returnType && class_exists($this->returnType)) {
                $setter = 'set' . ucfirst($field);
                if (method_exists($this->returnType, $setter)) {
                    try {
                        $entity = new $this->returnType();
                        $entity->{$setter}(null); // tes dulu
                    } catch (\RuntimeException $e) {
                        continue; // skip field ini
                    }
                }
            }
            $empty[$field] = null;
        }

        if ($returnEntity && $this->returnType && class_exists($this->returnType)) {
            return new $this->returnType($empty);
        }

        return $empty;
    }

    protected function transformStatus(array $data)
    {
        $field = 'status';
        if (isset($data['data'][$field])) {
            $data['data'][$field] = ($data['data'][$field] === 'on' || $data['data'][$field] === true || $data['data'][$field] === 1 || $data['data'][$field] === '1') ? 1 : 0;
        } else {
            if (isset($data[$field])) {
                $data[$field] = ($data[$field] === 'on' || $data[$field] === true || $data[$field] === 1 || $data[$field] === '1') ? 1 : 0;
            }
        }
        return $data;
    }

    protected function transformActive(array $data)
    {
        $field = 'active';
        if (isset($data['data'][$field])) {
            $data['data'][$field] = ($data['data'][$field] === 'on' || $data['data'][$field] === true || $data['data'][$field] === 1 || $data['data'][$field] === '1') ? 1 : 0;
        } else {

            if (isset($data[$field])) {
                $data[$field] = ($data[$field] === 'on' || $data[$field] === true || $data[$field] === 1 || $data[$field] === '1') ? 1 : 0;
            } 
        }
        return $data;
    }

    public function generateCode(array $data)
    {
        $field = $this->sequenceField ?? 'code';
        $table = $this->table;
        $prefix = $this->sequencePrefix ?? '';
        $format = $this->sequenceDateFormat ?? 'Ymd';
        $digits = $this->sequenceDigits ?? 4;

        helper('sequence');
        $data['data'][$field] = generate_sequence(
            $table,
            $prefix,
            $format,
            $digits,
            'monthly'
        );

        return $data;
    }
}
