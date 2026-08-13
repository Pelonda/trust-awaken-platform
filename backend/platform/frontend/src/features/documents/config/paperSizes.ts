export type PaperUnit =
  | 'mm'
  | 'cm'
  | 'in'
  | 'px'

export type PaperOrientation =
  | 'portrait'
  | 'landscape'

export type PaperCategory =
  | 'print'
  | 'card'
  | 'badge'
  | 'custom'

export interface PaperSize {
  key: string
  label: string
  category: PaperCategory

  width: number
  height: number

  unit: PaperUnit
}

export interface ResolvedPaperSize
  extends PaperSize {
  orientation:
    PaperOrientation
}

export const PAPER_SIZES:
  Record<string, PaperSize> = {
    a0: {
      key: 'a0',
      label: 'A0',
      category: 'print',
      width: 841,
      height: 1189,
      unit: 'mm',
    },

    a1: {
      key: 'a1',
      label: 'A1',
      category: 'print',
      width: 594,
      height: 841,
      unit: 'mm',
    },

    a2: {
      key: 'a2',
      label: 'A2',
      category: 'print',
      width: 420,
      height: 594,
      unit: 'mm',
    },

    a3: {
      key: 'a3',
      label: 'A3',
      category: 'print',
      width: 297,
      height: 420,
      unit: 'mm',
    },

    a4: {
      key: 'a4',
      label: 'A4',
      category: 'print',
      width: 210,
      height: 297,
      unit: 'mm',
    },

    a5: {
      key: 'a5',
      label: 'A5',
      category: 'print',
      width: 148,
      height: 210,
      unit: 'mm',
    },

    a6: {
      key: 'a6',
      label: 'A6',
      category: 'print',
      width: 105,
      height: 148,
      unit: 'mm',
    },

    letter: {
      key: 'letter',
      label: 'US Letter',
      category: 'print',
      width: 8.5,
      height: 11,
      unit: 'in',
    },

    legal: {
      key: 'legal',
      label: 'US Legal',
      category: 'print',
      width: 8.5,
      height: 14,
      unit: 'in',
    },

    tabloid: {
      key: 'tabloid',
      label: 'Tabloid / Ledger',
      category: 'print',
      width: 11,
      height: 17,
      unit: 'in',
    },

    cr80: {
      key: 'cr80',
      label: 'CR80 / ID-1',
      category: 'card',
      width: 85.6,
      height: 53.98,
      unit: 'mm',
    },

    id2: {
      key: 'id2',
      label: 'ID-2',
      category: 'card',
      width: 105,
      height: 74,
      unit: 'mm',
    },

    id3: {
      key: 'id3',
      label: 'ID-3',
      category: 'card',
      width: 125,
      height: 88,
      unit: 'mm',
    },

    'business-card-us': {
      key: 'business-card-us',
      label: 'Business Card — US',
      category: 'card',
      width: 3.5,
      height: 2,
      unit: 'in',
    },

    'business-card-eu': {
      key: 'business-card-eu',
      label: 'Business Card — EU',
      category: 'card',
      width: 85,
      height: 55,
      unit: 'mm',
    },

    'badge-square': {
      key: 'badge-square',
      label: 'Square Badge',
      category: 'badge',
      width: 1000,
      height: 1000,
      unit: 'px',
    },

    'badge-landscape': {
      key: 'badge-landscape',
      label: 'Landscape Badge',
      category: 'badge',
      width: 1200,
      height: 800,
      unit: 'px',
    },

    'badge-portrait': {
      key: 'badge-portrait',
      label: 'Portrait Badge',
      category: 'badge',
      width: 800,
      height: 1200,
      unit: 'px',
    },
  }

export function resolvePaperSize(
  key: string,
  orientation:
    PaperOrientation,
): ResolvedPaperSize {
  const size =
    PAPER_SIZES[key]

  if (!size) {
    throw new Error(
      `Unsupported paper size "${key}".`,
    )
  }

  let width =
    size.width

  let height =
    size.height

  if (
    orientation ===
      'portrait' &&
    width > height
  ) {
    ;[
      width,
      height,
    ] = [
      height,
      width,
    ]
  }

  if (
    orientation ===
      'landscape' &&
    height > width
  ) {
    ;[
      width,
      height,
    ] = [
      height,
      width,
    ]
  }

  return {
    ...size,
    width,
    height,
    orientation,
  }
}

export function physicalToCanvas(
  width: number,
  height: number,
  unit: PaperUnit,
): {
  width: number
  height: number
} {
  /*
   * Normalize print documents to a
   * practical Fabric coordinate system.
   *
   * The longest side becomes 1414px.
   * This preserves the physical aspect
   * ratio without creating enormous
   * browser canvases for A0/A1.
   */

  if (unit === 'px') {
    return {
      width:
        Math.round(width),

      height:
        Math.round(height),
    }
  }

  let widthMm =
    width

  let heightMm =
    height

  if (unit === 'cm') {
    widthMm *= 10
    heightMm *= 10
  }

  if (unit === 'in') {
    widthMm *= 25.4
    heightMm *= 25.4
  }

  const longest =
    Math.max(
      widthMm,
      heightMm,
    )

  const scale =
    1414 / longest

  return {
    width:
      Math.round(
        widthMm * scale,
      ),

    height:
      Math.round(
        heightMm * scale,
      ),
  }
}