<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\GradeType;

class StoreGradeRequest extends FormRequest
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
        $rules = [
            'student' => 'required|exists:users,id',
            'dosen'   => 'required|exists:users,id',
            'format'  => 'required|in:format1,format2,format3',
        ];

        
        $gradeTypeIds = GradeType::pluck('id')->map(fn ($id) => (string) $id)->toArray();

        
        foreach ($gradeTypeIds as $id) {
            $rules[$id] = $this->gradeRuleForFormat();
        }

        
        $this->replace(array_intersect_key(
            $this->all(),
            array_flip(array_merge(array_keys($rules), ['_token']))
        ));

        return $rules;
    }

    protected function gradeRuleForFormat(): string
    {
        $format = $this->input('format');

        return match ($format) {
            'format1' => 'required|integer|min:0|max:4',
            'format2' => 'required|integer|min:0|max:10',
            'format3' => 'required|integer|min:0|max:100',
            default   => 'required|integer|min:0|max:100',
        };
    }

    public function attributes(): array
{
    $penilaian = \App\Models\GradeType::all();
    $attributes = [
        'student' => 'Mahasiswa',
        'dosen'   => 'Dosen Penilai',
        'format'  => 'Format Penilaian',
    ];

    foreach ($penilaian as $nilai) {
        $attributes[(string) $nilai->id] = 'Nilai ' . $nilai->name;
    }

    return $attributes;
}
}
