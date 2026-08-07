import type { DocumentAsset } from './DocumentAsset'

export interface DocumentNode {

  id: string

  type: string

  x: number

  y: number

  width: number

  height: number

  rotation: number

  opacity: number

  visible: boolean

  locked: boolean

  text?: string

  asset?: DocumentAsset

}