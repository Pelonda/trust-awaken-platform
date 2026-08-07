export interface DocumentAsset {

  id: string

  name: string

  type:
    | 'image'
    | 'logo'
    | 'background'
    | 'watermark'
    | 'signature'
    | 'seal'

  url: string

  width: number

  height: number

  createdAt: string

}