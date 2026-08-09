export type DocumentAssetType =
  | 'image'
  | 'logo'
  | 'background'
  | 'watermark'
  | 'signature'
  | 'seal'

export interface DocumentAsset {
  id: string
  uuid: string

  organization_id: number

  name: string

  type: DocumentAssetType

  asset_type:
    DocumentAssetType

  disk: string

  path: string

  url: string

  mime_type:
    string | null

  width:
    number | null

  height:
    number | null

  metadata:
    Record<string, unknown> | null

  createdAt: string

  created_at: string

  updated_at: string
}