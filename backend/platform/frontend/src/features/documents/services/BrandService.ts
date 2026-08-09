import { api } from '../../../api/client'

const API_URL =
  '/credential-templates/document-brand'

export interface DocumentBrand {
  id: number
  uuid: string
  organization_id: number

  brand_name: string | null

  logo_path: string | null
  favicon_path: string | null
  background_path: string | null
  watermark_path: string | null
  signature_path: string | null
  seal_path: string | null

  primary_color: string | null
  secondary_color: string | null
  accent_color: string | null

  font_family: string | null

  settings:
    Record<string, unknown> | null

  created_at: string
  updated_at: string
  deleted_at?: string | null
}

export interface BrandPayload {
  brand_name?: string | null

  logo_path?: string | null
  favicon_path?: string | null
  background_path?: string | null
  watermark_path?: string | null
  signature_path?: string | null
  seal_path?: string | null

  primary_color?: string | null
  secondary_color?: string | null
  accent_color?: string | null

  font_family?: string | null

  settings?:
    Record<string, unknown> | null
}

interface ApiResponse<T> {
  data: T
}

export default class BrandService {
  static async get():
    Promise<DocumentBrand | null> {

    const response =
      await api.get<
        ApiResponse<DocumentBrand | null>
      >(
        API_URL,
      )

    return response.data.data
  }

  static async save(
    payload: BrandPayload,
  ): Promise<DocumentBrand> {

    const response =
      await api.put<
        ApiResponse<DocumentBrand>
      >(
        API_URL,
        payload,
      )

    return response.data.data
  }
}