<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'student_id' => $this->student_id,
            'inserted_balance' => $this->inserted_balance,
            'old_balance' => $this->old_balance,
            'updated_balance' => $this->current_balance,
        ];
    }
}
