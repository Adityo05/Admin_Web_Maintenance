@extends('layouts.app')

@section('title', 'Jadwal Pengecekan - ' . $assetName)

@section('content')
<div style="background-color: white; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <!-- Header with Back Button -->
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
        <a href="{{ route('check-sheet-template.index') }}" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background-color: #f5f5f5; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e0e0e0'" onmouseout="this.style.backgroundColor='#f5f5f5'">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h1 style="font-size: 24px; font-weight: bold; color: #022415; margin: 0;">
            Jadwal Pengecekan - {{ $assetName }}
        </h1>
    </div>

    <!-- Info Section -->
    <div style="background-color: #f8f9fa; border-left: 4px solid #0a9c5d; padding: 16px; border-radius: 6px; margin-bottom: 20px;">
        <div style="margin-bottom: 8px;">
            <span style="font-weight: 600; color: #022415;">Bagian:</span>
            <span style="color: #444; margin-left: 8px;">{{ $bagianName }} - {{ $komponenName }}</span>
        </div>
        <div style="margin-bottom: 12px;">
            <span style="font-weight: 600; color: #022415;">Jenis Pekerjaan:</span>
            <span style="color: #444; margin-left: 8px;">{{ $template->jenis_pekerjaan }}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 24px; font-size: 13px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-weight: 600;">Keterangan:</span>
                <span><strong>M</strong> = Rencana Perawatan</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #0a9c5d; font-weight: 600;"><strong>V</strong> = Aktual Perawatan</span>
            </div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div style="background-color: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px 20px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('check-sheet-template.kalender', ['id' => $template->id, 'month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" 
           style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background-color: #f5f5f5; text-decoration: none; transition: all 0.2s;"
           onmouseover="this.style.backgroundColor='#e0e0e0'"
           onmouseout="this.style.backgroundColor='#f5f5f5'">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        
        <h2 style="font-size: 18px; font-weight: 600; color: #022415; margin: 0;">
            {{ 
                ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$month] 
            }} {{ $year }}
        </h2>
        
        <a href="{{ route('check-sheet-template.kalender', ['id' => $template->id, 'month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" 
           style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background-color: #f5f5f5; text-decoration: none; transition: all 0.2s;"
           onmouseover="this.style.backgroundColor='#e0e0e0'"
           onmouseout="this.style.backgroundColor='#f5f5f5'">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
    </div>

    <!-- Calendar Grid -->
    <div style="background-color: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px;">
        <!-- Day Headers -->
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; margin-bottom: 8px;">
            @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
            <div style="text-align: center; font-weight: 600; font-size: 11px; color: #022415; padding: 8px 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ $day }}
            </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        @php
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $firstDay = date('w', strtotime("$year-$month-01")); // 0 = Sunday, 6 = Saturday
            $totalCells = 42; // 6 weeks x 7 days
        @endphp

        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px;">
            @for($i = 0; $i < $totalCells; $i++)
                @php
                    $dayNumber = $i - $firstDay + 1;
                    $isValidDay = $dayNumber >= 1 && $dayNumber <= $daysInMonth;
                    
                    $hasRencana = false;
                    $hasAktual = false;
                    $status = '';
                    
                    if ($isValidDay && isset($tanggalStatus[$dayNumber])) {
                        $status = $tanggalStatus[$dayNumber];
                        $hasRencana = true;
                        $hasAktual = str_contains($status, 'V');
                    }
                    
                    $bgColor = 'white';
                    $borderColor = '#e0e0e0';
                    $borderWidth = '0.5px';
                    
                    if ($hasAktual) {
                        $bgColor = 'rgba(10, 156, 93, 0.2)';
                        $borderColor = '#0a9c5d';
                        $borderWidth = '1.5px';
                    } elseif ($hasRencana) {
                        $bgColor = 'rgba(10, 156, 93, 0.05)';
                        $borderColor = '#0a9c5d';
                        $borderWidth = '1.5px';
                    }
                @endphp
                
                <div style="
                    aspect-ratio: 1;
                    background-color: {{ $bgColor }};
                    border: {{ $borderWidth }} solid {{ $borderColor }};
                    border-radius: 4px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 4px;
                    min-height: 70px;
                ">
                    @if($isValidDay)
                        <div style="font-size: 13px; font-weight: 600; color: {{ $hasAktual ? '#0a9c5d' : '#000' }}; margin-bottom: 2px;">
                            {{ $dayNumber }}
                        </div>
                        
                        @if($hasRencana)
                            <div style="font-size: 10px; font-weight: 600; color: #333; margin-top: 2px;">
                                M
                            </div>
                        @endif
                        
                        @if($hasAktual)
                            <div style="font-size: 10px; font-weight: 600; color: #0a9c5d; margin-top: 1px;">
                                V
                            </div>
                        @endif
                    @endif
                </div>
            @endfor
        </div>
    </div>
</div>
@endsection
