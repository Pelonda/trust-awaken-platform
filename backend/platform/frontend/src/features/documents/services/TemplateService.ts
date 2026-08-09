import type { StoreType } from 'polotno/model/store'

const API_URL =
  '/api/document-templates'

export interface SavedDocumentTemplate {
  id: number
  uuid: string
  organization_id: number | null
  name: string
  type: string
  paper_size: string
  orientation: 'portrait' | 'landscape'
  canvas: Record<string, unknown>
  default: boolean
  created_at: string
  updated_at: string
  deleted_at?: string | null
}

interface ApiResponse<T> {
  data: T
}

function organizationUuid(): string {
  const uuid =
    localStorage.getItem(
      'organization_uuid',
    )

  if (!uuid) {
    throw new Error(
      'No organization is selected.',
    )
  }

  return uuid
}

function headers(
  json = false,
): Record<string, string> {
  const result:
    Record<string, string> = {
      Accept: 'application/json',

      'X-Organization':
        organizationUuid(),
    }

  if (json) {
    result['Content-Type'] =
      'application/json'
  }

  const token =
    localStorage.getItem(
      'awaken_token',
    )

  if (token) {
    result.Authorization =
      `Bearer ${token}`
  }

  return result
}

export default class TemplateService {
  static async create(
    store: StoreType,
    name: string,
  ): Promise<SavedDocumentTemplate> {
    const response = await fetch(
      API_URL,
      {
        method: 'POST',

        headers:
          headers(true),

        credentials:
          'include',

        body: JSON.stringify({
          name,

          type:
            'certificate',

          paper_size:
            'a4',

          orientation:
            'landscape',

          canvas:
            store.toJSON(),

          default:
            false,
        }),
      },
    )

    return this.handleOne(
      response,
      'Unable to create template.',
    )
  }

  static async update(
    store: StoreType,
    id: number,
    name: string,
  ): Promise<SavedDocumentTemplate> {
    const response = await fetch(
      `${API_URL}/${id}`,
      {
        method: 'PUT',

        headers:
          headers(true),

        credentials:
          'include',

        body: JSON.stringify({
          name,

          canvas:
            store.toJSON(),
        }),
      },
    )

    return this.handleOne(
      response,
      'Unable to update template.',
    )
  }

  static async save(
    store: StoreType,
    name: string,
    id?: number | null,
  ): Promise<SavedDocumentTemplate> {
    if (id) {
      return this.update(
        store,
        id,
        name,
      )
    }

    return this.create(
      store,
      name,
    )
  }

  static async load(
    store: StoreType,
    id: number,
  ): Promise<SavedDocumentTemplate> {
    const response = await fetch(
      `${API_URL}/${id}`,
      {
        headers:
          headers(),

        credentials:
          'include',
      },
    )

    const template =
      await this.handleOne(
        response,
        'Unable to load template.',
      )

    store.loadJSON(
      template.canvas as any,
    )

    return template
  }

  static async all():
    Promise<SavedDocumentTemplate[]> {
    const response = await fetch(
      API_URL,
      {
        headers:
          headers(),

        credentials:
          'include',
      },
    )

    if (!response.ok) {
      throw new Error(
        await this.errorMessage(
          response,
          'Unable to retrieve templates.',
        ),
      )
    }

    const result:
      ApiResponse<
        SavedDocumentTemplate[]
      > =
        await response.json()

    return result.data
  }

  static async remove(
    id: number,
  ): Promise<void> {
    const response = await fetch(
      `${API_URL}/${id}`,
      {
        method: 'DELETE',

        headers:
          headers(),

        credentials:
          'include',
      },
    )

    if (!response.ok) {
      throw new Error(
        await this.errorMessage(
          response,
          'Unable to delete template.',
        ),
      )
    }
  }

  static async setDefault(
    template:
      SavedDocumentTemplate,
  ): Promise<SavedDocumentTemplate> {
    const response = await fetch(
      `${API_URL}/${template.id}`,
      {
        method: 'PATCH',

        headers:
          headers(true),

        credentials:
          'include',

        body: JSON.stringify({
          default: true,
        }),
      },
    )

    return this.handleOne(
      response,
      'Unable to set default template.',
    )
  }

  static async duplicate(
    template:
      SavedDocumentTemplate,
  ): Promise<SavedDocumentTemplate> {
    const response = await fetch(
      API_URL,
      {
        method: 'POST',

        headers:
          headers(true),

        credentials:
          'include',

        body: JSON.stringify({
          name:
            `${template.name} Copy`,

          type:
            template.type,

          paper_size:
            template.paper_size,

          orientation:
            template.orientation,

          canvas:
            template.canvas,

          default:
            false,
        }),
      },
    )

    return this.handleOne(
      response,
      'Unable to duplicate template.',
    )
  }

  private static async handleOne(
    response: Response,
    fallback: string,
  ): Promise<SavedDocumentTemplate> {
    if (!response.ok) {
      throw new Error(
        await this.errorMessage(
          response,
          fallback,
        ),
      )
    }

    const result:
      ApiResponse<
        SavedDocumentTemplate
      > =
        await response.json()

    return result.data
  }

  private static async errorMessage(
    response: Response,
    fallback: string,
  ): Promise<string> {
    try {
      const data =
        await response.json()

      return (
        data.message ??
        fallback
      )
    } catch {
      return fallback
    }
  }
}