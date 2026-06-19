<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Support;

use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use App\Modules\PPDB\Models\Registration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RegistrationsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    protected AdmissionTrack $track;
    protected $fields;

    public function __construct(AdmissionTrack $track)
    {
        $this->track = $track;
        $this->fields = AdmissionFormField::where('track_id', $track->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Define headings.
     */
    public function headings(): array
    {
        $headings = [
            'Nomor Pendaftaran',
            'Status',
            'Tanggal Pendaftaran',
        ];

        foreach ($this->fields as $field) {
            $headings[] = $field->label;
        }

        return $headings;
    }

    /**
     * Query registrations to export.
     */
    public function query()
    {
        return Registration::where('track_id', $this->track->id)
            ->with(['values.field', 'documents.field']);
    }

    /**
     * Map each registration record to output row.
     *
     * @param mixed $registration
     * @return array
     */
    public function map($registration): array
    {
        $row = [
            $registration->registration_number,
            $registration->status,
            $registration->submitted_at ? $registration->submitted_at->format('Y-m-d H:i:s') : '-',
        ];

        foreach ($this->fields as $field) {
            if ($field->type === 'file') {
                $doc = $registration->documents->firstWhere('field_id', $field->id);
                $row[] = $doc ? $doc->original_name : '-';
            } else {
                $valModel = $registration->values->firstWhere('field_id', $field->id);
                if ($valModel) {
                    $val = $valModel->real_value;
                    if (is_array($val)) {
                        $row[] = implode(', ', $val);
                    } else {
                        $row[] = is_bool($val) ? ($val ? 'Ya' : 'Tidak') : strval($val);
                    }
                } else {
                    $row[] = '-';
                }
            }
        }

        return $row;
    }

    /**
     * Define chunk size for streaming.
     */
    public function chunkSize(): int
    {
        return 200;
    }
}
