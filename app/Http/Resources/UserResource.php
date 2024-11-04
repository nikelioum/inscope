<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'lastname' => $this->lastname,
        'email' => $this->email,
        'role' => $this->role ? $this->role->name : null,
        'companies' => $this->companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->name,
                'address' => $company->address,
            ];
        }),
    ];
}

}
