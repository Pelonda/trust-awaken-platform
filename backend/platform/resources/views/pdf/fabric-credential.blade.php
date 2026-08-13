<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Fabric Credential
    </title>

    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: {{ $canvasWidth }}px;
            height: {{ $canvasHeight }}px;
            background: {{ $background }};
            overflow: hidden;
        }

        body {
            font-family: Arial, DejaVu Sans, sans-serif;
        }

        .fabric-page {
            position: relative;
            width: {{ $canvasWidth }}px;
            height: {{ $canvasHeight }}px;
            overflow: hidden;
            background: {{ $background }};
        }

        .fabric-object {
            position: absolute;
            box-sizing: border-box;
            transform-origin: center center;
            white-space: pre-wrap;
            overflow: hidden;
        }

        .fabric-text {
            display: block;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .fabric-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: fill;
        }

        .fabric-rect {
            box-sizing: border-box;
        }
    </style>
</head>

<body>
<div
    class="fabric-page"
    style="
        width: {{ $canvasWidth }}px;
        height: {{ $canvasHeight }}px;
        background: {{ $background }};
    "
>
    @foreach ($objects as $object)
        @php
            $left =
                number_format(
                    (float) ($object['left'] ?? 0),
                    4,
                    '.',
                    ''
                );

            $top =
                number_format(
                    (float) ($object['top'] ?? 0),
                    4,
                    '.',
                    ''
                );

            $width =
                number_format(
                    (float) ($object['width'] ?? 0),
                    4,
                    '.',
                    ''
                );

            $height =
                number_format(
                    (float) ($object['height'] ?? 0),
                    4,
                    '.',
                    ''
                );

            $angle =
                number_format(
                    (float) ($object['angle'] ?? 0),
                    4,
                    '.',
                    ''
                );

            $opacity =
                number_format(
                    (float) ($object['opacity'] ?? 1),
                    4,
                    '.',
                    ''
                );

            $transform =
                'rotate('
                . $angle
                . 'deg)';

            $border =
                !empty(
                    $object['stroke']
                )
                    ? 'border: '
                        . (
                            floatval(
                                $object['strokeWidth']
                                    ?? 0
                            )
                        )
                        . 'px solid '
                        . $object['stroke']
                        . ';'
                    : '';

            $type =
                $object['type']
                    ?? '';
        @endphp

        @continue(
            !(
                $object['visible']
                    ?? true
            )
        )

        @if (
            $type === 'Text' ||
            $type === 'IText' ||
            $type === 'Textbox'
        )
            @php
                $fontWeight =
                    $object['fontWeight']
                        ?? 'normal';

                $fontStyle =
                    $object['fontStyle']
                        ?? 'normal';

                $textAlign =
                    $object['textAlign']
                        ?? 'left';

                $decoration = '';

                if (
                    !empty(
                        $object['underline']
                    )
                ) {
                    $decoration =
                        'underline';
                }

                if (
                    !empty(
                        $object['linethrough']
                    )
                ) {
                    $decoration =
                        $decoration
                        ? $decoration
                            . ' line-through'
                        : 'line-through';
                }
            @endphp

            <div
                class="fabric-object fabric-text"
                style="
                    left: {{ $left }}px;
                    top: {{ $top }}px;
                    width: {{ $width }}px;
                    height: {{ $height }}px;
                    opacity: {{ $opacity }};
                    transform: {{ $transform }};
                    color: {{ $object['fill'] ?? '#000000' }};
                    font-family: {{ $object['fontFamily'] ?? 'Arial' }}, DejaVu Sans, sans-serif;
                    font-size: {{ $object['fontSize'] ?? 16 }}px;
                    font-weight: {{ $fontWeight }};
                    font-style: {{ $fontStyle }};
                    line-height: {{ $object['lineHeight'] ?? 1.16 }};
                    text-align: {{ $textAlign }};
                    text-decoration: {{ $decoration ?: 'none' }};
                "
            >{{ $object['text'] ?? '' }}</div>

        @elseif (
            $type === 'Rect'
        )
            <div
                class="fabric-object fabric-rect"
                style="
                    left: {{ $left }}px;
                    top: {{ $top }}px;
                    width: {{ $width }}px;
                    height: {{ $height }}px;
                    opacity: {{ $opacity }};
                    transform: {{ $transform }};
                    background: {{ $object['fill'] ?? 'transparent' }};
                    {{ $border }}
                "
            ></div>

        @elseif (
            $type === 'Image'
        )
            @if (!empty($object['src']))
                <img
                    class="fabric-object fabric-image"
                    src="{{ $object['src'] }}"
                    alt=""
                    style="
                        left: {{ $left }}px;
                        top: {{ $top }}px;
                        width: {{ $width }}px;
                        height: {{ $height }}px;
                        opacity: {{ $opacity }};
                        transform: {{ $transform }};
                        {{ $border }}
                    "
                >
            @endif
        @endif
    @endforeach
</div>
</body>
</html>