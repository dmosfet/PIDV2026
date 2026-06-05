<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Informations générales
            'reception_date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,category_id'],
            'channel_id' => ['required', 'exists:channels,channel_id'],
            'complaint_type_id' => ['required', 'exists:complaint_types,complaint_type_id'],

            // Plaignant (sera créé ou trouvé)
            'complainant_email' => ['required', 'email', 'max:255'],
            'complainant_lastname' => ['required', 'string', 'max:255'],
            'complainant_firstname' => ['required', 'string', 'max:50'],

            // Client (sera créé ou trouvé via external_id)
            'customer_id' => ['nullable', 'integer', 'min:0'],

            // Parties concernées optionnelles
            'employee_id' => ['nullable', 'exists:employees,employee_id'],
            'entity_id' => ['nullable', 'exists:entities,entity_id'],
            'profession_id' => ['nullable', 'exists:professions,profession_id'],

            // Traitement et suivi
            'acknowledgment_date' => ['nullable', 'date', 'after_or_equal:reception_date'],
            'transmission_date' => ['nullable', 'date', 'after_or_equal:reception_date'],
            'admissible' => ['nullable', 'boolean'],
            'well_founded' => ['nullable', 'boolean'],
            'duration' => ['nullable', 'integer', 'min:0'],

            // Réponse
            'response_date' => ['nullable', 'date', 'after_or_equal:reception_date'],
            'response' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'reception_date' => 'date de réception',
            'category_id' => 'catégorie',
            'complaint_type_id' => 'type de plainte',
            'channel_id' => 'canal de réception',
            'complainant_lastname' => 'nom du plaignant',
            'complainant_firstname' => 'prénom du plaignant',
            'complainant_email' => 'email du plaignant',
            'customer_id' => 'ID client',
            'employee_id' => 'employé',
            'entity_id' => 'entité',
            'profession_id' => 'profession',
            'acknowledgment_date' => 'date d\'accusé de réception',
            'transmission_date' => 'date de transmission',
            'admissible' => 'admissibilité',
            'well_founded' => 'bien-fondé',
            'duration' => 'durée',
            'response_date' => 'date de réponse',
            'response' => 'réponse',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'acknowledgment_date.after_or_equal' => 'La date d\'accusé de réception doit être postérieure ou égale à la date de réception.',
            'transmission_date.after_or_equal' => 'La date de transmission doit être postérieure ou égale à la date de réception.',
            'response_date.after_or_equal' => 'La date de réponse doit être postérieure ou égale à la date de réception.',
        ];
    }
}
