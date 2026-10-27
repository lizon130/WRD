{{--
    All Units Summary card — PDF safe (DomPDF compatible)
    Vars: $perfTotals (array), $pUW / $pIW / $pDW (0-100 percentage for bar fill)
--}}
<table style="width: 100%; border-collapse: collapse; border: 1px solid #2c3e50; border-radius: 4px; overflow: hidden;">

    {{-- Header --}}
    <tr>
        <td colspan="3" style="
            background-color: #1a2a3a;
            padding: 6px 8px;
            text-align: center;
        ">
            <span style="font-size: 8.5px; font-weight: bold; color: #ffffff; letter-spacing: 0.5px; text-transform: uppercase;">
                All Units Summary
            </span>
            <br>
            <span style="font-size: 7px; color: #a0b4c8;">
                {{ $perfTotals['units'] }} Units &nbsp;|&nbsp; Machine Performance
            </span>
        </td>
    </tr>

    {{-- Uptime row --}}
    <tr style="background-color: #f0faf2;">
        <td style="
            padding: 5px 7px;
            font-size: 7.5px;
            font-weight: bold;
            color: #155724;
            width: 50px;
            border-bottom: 1px solid #d4edda;
            border-right: 1px solid #d4edda;
            white-space: nowrap;
        ">
            UPTIME
        </td>
        <td style="
            padding: 4px 5px;
            border-bottom: 1px solid #d4edda;
            border-right: 1px solid #d4edda;
        ">
            <table style="width: 100%; border-collapse: collapse; border: 0;">
                <tr>
                    <td style="padding: 0; border: 0; background-color: #e8f5e9; border-radius: 3px;">
                        <div style="
                            width: {{ $pUW }}%;
                            height: 11px;
                            background-color: #28a745;
                            border-radius: 3px;
                        "></div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="
            padding: 5px 7px;
            font-size: 8px;
            font-weight: bold;
            color: #155724;
            text-align: right;
            border-bottom: 1px solid #d4edda;
            white-space: nowrap;
            width: 32px;
        ">
            {{ $perfTotals['uptime'] }}%
        </td>
    </tr>

    {{-- Idletime row --}}
    <tr style="background-color: #fffdf0;">
        <td style="
            padding: 5px 7px;
            font-size: 7.5px;
            font-weight: bold;
            color: #6d4c00;
            width: 50px;
            border-bottom: 1px solid #ffeeba;
            border-right: 1px solid #ffeeba;
            white-space: nowrap;
        ">
            IDLETIME
        </td>
        <td style="
            padding: 4px 5px;
            border-bottom: 1px solid #ffeeba;
            border-right: 1px solid #ffeeba;
        ">
            <table style="width: 100%; border-collapse: collapse; border: 0;">
                <tr>
                    <td style="padding: 0; border: 0; background-color: #fff8e1; border-radius: 3px;">
                        <div style="
                            width: {{ $pIW }}%;
                            height: 11px;
                            background-color: #e6a817;
                            border-radius: 3px;
                        "></div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="
            padding: 5px 7px;
            font-size: 8px;
            font-weight: bold;
            color: #6d4c00;
            text-align: right;
            border-bottom: 1px solid #ffeeba;
            white-space: nowrap;
            width: 32px;
        ">
            {{ $perfTotals['idletime'] }}%
        </td>
    </tr>

    {{-- Downtime row --}}
    <tr style="background-color: #fff5f5;">
        <td style="
            padding: 5px 7px;
            font-size: 7.5px;
            font-weight: bold;
            color: #7b1c1c;
            width: 50px;
            border-bottom: 1px solid #f5c6cb;
            border-right: 1px solid #f5c6cb;
            white-space: nowrap;
        ">
            DOWNTIME
        </td>
        <td style="
            padding: 4px 5px;
            border-bottom: 1px solid #f5c6cb;
            border-right: 1px solid #f5c6cb;
        ">
            <table style="width: 100%; border-collapse: collapse; border: 0;">
                <tr>
                    <td style="padding: 0; border: 0; background-color: #fde8ea; border-radius: 3px;">
                        <div style="
                            width: {{ $pDW }}%;
                            height: 11px;
                            background-color: #dc3545;
                            border-radius: 3px;
                        "></div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="
            padding: 5px 7px;
            font-size: 8px;
            font-weight: bold;
            color: #7b1c1c;
            text-align: right;
            border-bottom: 1px solid #f5c6cb;
            white-space: nowrap;
            width: 32px;
        ">
            {{ $perfTotals['downtime'] }}%
        </td>
    </tr>

    {{-- Footer legend --}}
    <tr>
        <td colspan="3" style="
            background-color: #f2f4f6;
            padding: 4px 8px;
            border-top: 1px solid #ced4da;
        ">
            <table style="width: 100%; border: 0; border-collapse: collapse;">
                <tr>
                    <td style="border: 0; padding: 0; text-align: center;">
                        <span style="display: inline-block; width: 8px; height: 8px; background-color: #28a745; border-radius: 1px; vertical-align: middle;"></span>
                        <span style="font-size: 6.5px; color: #155724; font-weight: bold; vertical-align: middle;"> Uptime</span>
                        &nbsp;&nbsp;
                        <span style="display: inline-block; width: 8px; height: 8px; background-color: #e6a817; border-radius: 1px; vertical-align: middle;"></span>
                        <span style="font-size: 6.5px; color: #6d4c00; font-weight: bold; vertical-align: middle;"> Idletime</span>
                        &nbsp;&nbsp;
                        <span style="display: inline-block; width: 8px; height: 8px; background-color: #dc3545; border-radius: 1px; vertical-align: middle;"></span>
                        <span style="font-size: 6.5px; color: #7b1c1c; font-weight: bold; vertical-align: middle;"> Downtime</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
