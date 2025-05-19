<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceEditRequest extends FormRequest
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
            'clock_in' => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i'],
            'break_start' => ['nullable', 'date_format:H:i'],
            'break_end' => ['nullable', 'date_format:H:i'],
            'note' => ['required', 'string'],
        ];

    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clockIn = $this->input('clock_in');
            $clockOut = $this->input('clock_out');

            // 出勤・退勤の妥当性チェック
            if ($clockIn && $clockOut && $clockIn >= $clockOut) {
                $validator->errors()->add('clock_out', '出勤時間もしくは退勤時間が不適切な値です');
            }

            foreach ($this->input('break_times', []) as $i => $break) {
                $start = $break['start'] ?? null;
                $end = $break['end'] ?? null;

                if ($start && ($start < $clockIn || $start > $clockOut)) {
                    $validator->errors()->add("break_times.$i.start", '休憩時間が勤務時間外です');
                }

                if ($end && ($end < $clockIn || $end > $clockOut)) {
                    $validator->errors()->add("break_times.$i.end", '休憩時間が勤務時間外です');
                }

                if ($start && $end && $start >= $end) {
                    $validator->errors()->add("break_times.$i.end", '休憩終了時間は休憩開始時間より後にしてください');
                }
            }

        });
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_in.date_format' => '出勤時間の形式が正しくありません',
            'clock_out.date_format' => '退勤時間の形式が正しくありません',
            'note.required' => '備考を記入してください',
        ];
    }
}
