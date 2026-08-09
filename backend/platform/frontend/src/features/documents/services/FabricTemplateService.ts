const API_URL =
  '/api/document-templates'

export interface FabricCanvasJSON {
  engine?: 'fabric'
  engine_version?: number

  version?: string
  objects?: unknown[]
  background?: string

  [key: string]: unknown
}

export interface SavedFabricTemplate {
  id: number
  uuid: string
  organization_id: number | null

  name: string
  type: string

  paper_size: string

  orientation:
    | 'portrait'
    | 'landscape'

  canvas: FabricCanvasJSON

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
      Accept:
        'application/json',

      'X-Organization':
        organizationUuid(),
    }

  const token =
    localStorage.getItem(
      'awaken_token',
    )

  if (token) {
    result.Authorization =
      `Bearer ${token}`
  }

  if (json) {
    result['Content-Type'] =
      'application/json'
  }

  return result
}

function prepareCanvas(
  canvas: FabricCanvasJSON,
): FabricCanvasJSON {
  return {
    ...canvas,

    engine: 'fabric',

    engine_version: 7,
  }
}

function isFabricTemplate(
  template: SavedFabricTemplate,
): boolean {
  return (
    template.canvas?.engine ===
    'fabric'
  )
}

export default class FabricTemplateService {
  static async create(
    name: string,
    canvas: FabricCanvasJSON,
  ): Promise<SavedFabricTemplate> {
    const response =
      await fetch(
        API_URL,
        {
          method: 'POST',

          headers:
            headers(true),

          credentials:
            'include',

          body:
            JSON.stringify({
              name,

              type:
                'certificate',

              paper_size:
                'custom',

              orientation:
                'landscape',

              canvas:
                prepareCanvas(
                  canvas,
                ),

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
    id: number,
    name: string,
    canvas: FabricCanvasJSON,
  ): Promise<SavedFabricTemplate> {
    const response =
      await fetch(
        `${API_URL}/${id}`,
        {
          method: 'PUT',

          headers:
            headers(true),

          credentials:
            'include',

          body:
            JSON.stringify({
              name,

              canvas:
                prepareCanvas(
                  canvas,
                ),
            }),
        },
      )

    return this.handleOne(
      response,
      'Unable to update template.',
    )
  }

  static async save(
    name: string,
    canvas: FabricCanvasJSON,
    id?: number | null,
  ): Promise<SavedFabricTemplate> {
    if (id) {
      return this.update(
        id,
        name,
        canvas,
      )
    }

    return this.create(
      name,
      canvas,
    )
  }

  static async all():
    Promise<SavedFabricTemplate[]> {
    const response =
      await fetch(
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
        SavedFabricTemplate[]
      > =
        await response.json()

    return result.data.filter(
      isFabricTemplate,
    )
  }

  static async get(
    id: number,
  ): Promise<SavedFabricTemplate> {
    const response =
      await fetch(
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

    if (
      !isFabricTemplate(
        template,
      )
    ) {
      throw new Error(
        'This template was created with another editor and cannot be loaded into Fabric Studio.',
      )
    }

    return template
  }

  static async remove(
    id: number,
  ): Promise<void> {
    const response =
      await fetch(
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

  static async duplicate(
    template:
      SavedFabricTemplate,
  ): Promise<SavedFabricTemplate> {
    if (
      !isFabricTemplate(
        template,
      )
    ) {
      throw new Error(
        'Only Fabric templates can be duplicated here.',
      )
    }

    const response =
      await fetch(
        API_URL,
        {
          method: 'POST',

          headers:
            headers(true),

          credentials:
            'include',

          body:
            JSON.stringify({
              name:
                `${template.name} Copy`,

              type:
                template.type,

              paper_size:
                template.paper_size,

              orientation:
                template.orientation,

              canvas:
                prepareCanvas(
                  template.canvas,
                ),

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

  static async setDefault(
    id: number,
  ): Promise<SavedFabricTemplate> {
    const response =
      await fetch(
        `${API_URL}/${id}`,
        {
          method:
            'PATCH',

          headers:
            headers(true),

          credentials:
            'include',

          body:
            JSON.stringify({
              default: true,
            }),
        },
      )

    return this.handleOne(
      response,
      'Unable to set default template.',
    )
  }

  private static async handleOne(
    response: Response,
    fallback: string,
  ): Promise<SavedFabricTemplate> {
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
        SavedFabricTemplate
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