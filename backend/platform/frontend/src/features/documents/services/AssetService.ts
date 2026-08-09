import { api } from '../../../api/client'

import type {
  DocumentAsset,
} from '../models/DocumentAsset'

const API_URL =
  '/credential-templates/document-assets'

export type DocumentAssetType =
  | 'image'
  | 'logo'
  | 'background'
  | 'watermark'
  | 'signature'
  | 'seal'

interface ApiResponse<T> {
  data: T
}

export default class AssetService {
  static async all():
    Promise<DocumentAsset[]> {
    const response =
      await api.get<
        ApiResponse<DocumentAsset[]>
      >(
        API_URL,
      )

    return response.data.data
  }

  static async create(
    file: File,
    type:
      DocumentAssetType = 'image',
    name?: string,
  ): Promise<DocumentAsset> {
    const form =
      new FormData()

    form.append(
      'file',
      file,
    )

    form.append(
      'asset_type',
      type,
    )

    if (name) {
      form.append(
        'name',
        name,
      )
    }

    /*
     * Important:
     * Do not set Content-Type manually.
     *
     * Axios/browser must generate the
     * multipart/form-data boundary.
     */
    const response =
      await api.post<
        ApiResponse<DocumentAsset>
      >(
        API_URL,
        form,
      )

    return response.data.data
  }

  static async remove(
    uuid: string,
  ): Promise<void> {
    await api.delete(
      `${API_URL}/${uuid}`,
    )
  }
}