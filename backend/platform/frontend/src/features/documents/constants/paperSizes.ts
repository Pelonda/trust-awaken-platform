export interface PaperSize {

  id: string

  name: string

  width: number

  height: number

}

export const PAPER_SIZES: PaperSize[] = [

  {
    id: 'a4-landscape',
    name: 'A4 Landscape',
    width: 1123,
    height: 794,
  },

  {
    id: 'a4-portrait',
    name: 'A4 Portrait',
    width: 794,
    height: 1123,
  },

  {
    id: 'letter-landscape',
    name: 'Letter Landscape',
    width: 1056,
    height: 816,
  },

  {
    id: 'letter-portrait',
    name: 'Letter Portrait',
    width: 816,
    height: 1056,
  },

  {
    id: 'badge',
    name: 'Badge',
    width: 816,
    height: 504,
  },

  {
    id: 'id-card',
    name: 'ID Card',
    width: 1011,
    height: 638,
  },

]