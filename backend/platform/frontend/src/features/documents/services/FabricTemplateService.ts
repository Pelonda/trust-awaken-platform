const API_URL =
  '/api/document-templates'

export type DocumentType =
  | 'certificate'
  | 'diploma'
  | 'badge'
  | 'id_card'
  | 'training_card'
  | 'custom'

export type DocumentLanguage =
  | 'en'
  | 'fr'
  | 'en-fr'

export type PaperUnit =
  | 'mm'
  | 'cm'
  | 'in'
  | 'px'

export type PaperOrientation =
  | 'portrait'
  | 'landscape'

export interface FabricPageJSON {
  key: string

  canvas_width: number

  canvas_height: number

  background?: string

  objects: unknown[]

  version?: string
}

export interface FabricPaperJSON {
  preset: string

  width: number

  height: number

  unit: PaperUnit

  orientation:
    PaperOrientation

  label?: string

  category?: string
}

/*
|--------------------------------------------------------------------------
| Fabric Canvas JSON
|--------------------------------------------------------------------------
|
| V1 fields remain supported for legacy templates.
|
| V2 adds:
|
| - schema version
| - document type
| - language
| - physical paper metadata
| - multiple pages / front-back
|
*/

export interface FabricCanvasJSON {
  schema_version?: number

  engine?: 'fabric'

  engine_version?: number

  document_type?:
    DocumentType

  language?:
    DocumentLanguage

  paper?:
    FabricPaperJSON

  pages?:
    FabricPageJSON[]

  /*
   * V1 / active-page compatibility.
   */

  canvas_width?: number

  canvas_height?: number

  version?: string

  objects?: unknown[]

  background?: string
}

/*
|--------------------------------------------------------------------------
| Saved Template
|--------------------------------------------------------------------------
*/

export interface SavedFabricTemplate {
  id: number

  uuid: string

  organization_id:
    number | null

  name: string

  /*
   * Legacy classification.
   */

  type: string

  paper_size: string

  orientation:
    PaperOrientation

  /*
   * Template Schema V2.
   */

  schema_version?:
    number

  document_type?:
    DocumentType

  language?:
    DocumentLanguage

  paper_width?:
    number | string | null

  paper_height?:
    number | string | null

  paper_unit?:
    PaperUnit | null

  /*
   * Professional system-library fields.
   */

  is_system?:
    boolean

  source_template_id?:
    number | null

  settings?:
    Record<string, unknown> | null

  /*
   * Fabric document.
   */

  canvas:
    FabricCanvasJSON

  default:
    boolean

  created_at:
    string

  updated_at:
    string

  deleted_at?:
    string | null
}

/*
|--------------------------------------------------------------------------
| Save Options
|--------------------------------------------------------------------------
*/

export interface SaveFabricTemplateOptions {
  documentType:
    DocumentType

  language:
    DocumentLanguage

  paperSize:
    string

  orientation:
    PaperOrientation

  paperWidth:
    number

  paperHeight:
    number

  paperUnit:
    PaperUnit

  schemaVersion?:
    number

  settings?:
    Record<string, unknown> | null
}

interface ApiResponse<T> {
  data: T
}

/*
|--------------------------------------------------------------------------
| Organization
|--------------------------------------------------------------------------
*/

function organizationUuid():
  string {
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

/*
|--------------------------------------------------------------------------
| Request Headers
|--------------------------------------------------------------------------
*/

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
    result[
      'Content-Type'
    ] =
      'application/json'
  }

  return result
}

/*
|--------------------------------------------------------------------------
| Prepare Canvas
|--------------------------------------------------------------------------
*/

function prepareCanvas(
  canvas:
    FabricCanvasJSON,
): FabricCanvasJSON {
  return {
    ...canvas,

    schema_version:
      canvas.schema_version ??
      2,

    engine:
      'fabric',

    engine_version:
      7,
  }
}

/*
|--------------------------------------------------------------------------
| Fabric Template Guard
|--------------------------------------------------------------------------
*/

function isFabricTemplate(
  template:
    SavedFabricTemplate,
): boolean {
  return (
    template.canvas?.engine ===
    'fabric'
  )
}

/*
|--------------------------------------------------------------------------
| Save Payload
|--------------------------------------------------------------------------
*/

function createPayload(
  name: string,
  canvas:
    FabricCanvasJSON,
  options:
    SaveFabricTemplateOptions,
) {
  return {
    name,

    /*
     * Keep the legacy `type` field
     * synchronized with V2.
     */

    type:
      options.documentType,

    paper_size:
      options.paperSize,

    orientation:
      options.orientation,

    canvas:
      prepareCanvas(
        canvas,
      ),

    default:
      false,

    schema_version:
      options.schemaVersion ??
      2,

    document_type:
      options.documentType,

    language:
      options.language,

    paper_width:
      options.paperWidth,

    paper_height:
      options.paperHeight,

    paper_unit:
      options.paperUnit,

    settings:
      options.settings ??
      null,
  }
}

/*
|--------------------------------------------------------------------------
| Fabric Template Service
|--------------------------------------------------------------------------
*/

export default class FabricTemplateService {
  /*
  |--------------------------------------------------------------------------
  | Create Tenant Template
  |--------------------------------------------------------------------------
  */

  static async create(
    name: string,
    canvas:
      FabricCanvasJSON,
    options:
      SaveFabricTemplateOptions,
  ): Promise<SavedFabricTemplate> {
    const response =
      await fetch(
        API_URL,
        {
          method:
            'POST',

          headers:
            headers(
              true,
            ),

          credentials:
            'include',

          body:
            JSON.stringify(
              createPayload(
                name,
                canvas,
                options,
              ),
            ),
        },
      )

    return this.handleOne(
      response,
      'Unable to create template.',
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Update Tenant Template
  |--------------------------------------------------------------------------
  */

  static async update(
    id: number,
    name: string,
    canvas:
      FabricCanvasJSON,
    options:
      SaveFabricTemplateOptions,
  ): Promise<SavedFabricTemplate> {
    const payload =
      createPayload(
        name,
        canvas,
        options,
      )

    /*
     * Normal editing must not change
     * default status.
     */

    const {
      default:
        _default,
      ...updatePayload
    } =
      payload

    const response =
      await fetch(
        `${API_URL}/${id}`,
        {
          method:
            'PUT',

          headers:
            headers(
              true,
            ),

          credentials:
            'include',

          body:
            JSON.stringify(
              updatePayload,
            ),
        },
      )

    return this.handleOne(
      response,
      'Unable to update template.',
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Save
  |--------------------------------------------------------------------------
  */

  static async save(
    name: string,
    canvas:
      FabricCanvasJSON,
    options:
      SaveFabricTemplateOptions,
    id?: number | null,
  ): Promise<SavedFabricTemplate> {
    if (id) {
      return this.update(
        id,
        name,
        canvas,
        options,
      )
    }

    return this.create(
      name,
      canvas,
      options,
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Tenant Templates
  |--------------------------------------------------------------------------
  */

  static async all():
    Promise<
      SavedFabricTemplate[]
    > {
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

  /*
  |--------------------------------------------------------------------------
  | Professional System Library
  |--------------------------------------------------------------------------
  */

  static async library():
    Promise<
      SavedFabricTemplate[]
    > {
    const response =
      await fetch(
        `${API_URL}/library`,
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
          'Unable to retrieve the professional template library.',
        ),
      )
    }

    const result:
      ApiResponse<
        SavedFabricTemplate[]
      > =
      await response.json()

    return result.data.filter(
      template =>
        isFabricTemplate(
          template,
        ) &&
        Boolean(
          template.is_system,
        ),
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Use Professional Template
  |--------------------------------------------------------------------------
  |
  | This does NOT edit the system master.
  |
  | Laravel creates an independent tenant
  | copy with source_template_id pointing
  | to the professional master.
  |
  */

  static async useTemplate(
    template:
      SavedFabricTemplate,
    name?: string,
  ): Promise<
    SavedFabricTemplate
  > {
    if (
      !template.is_system
    ) {
      throw new Error(
        'Only professional system templates can be added to your organization.',
      )
    }

    const requestedName =
      name?.trim()

    const response =
      await fetch(
        `${API_URL}/${template.id}/use`,
        {
          method:
            'POST',

          headers:
            headers(
              true,
            ),

          credentials:
            'include',

          body:
            JSON.stringify({
              name:
                requestedName ||
                template.name,
            }),
        },
      )

    return this.handleOne(
      response,
      'Unable to add this template to your organization.',
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Get Tenant Template
  |--------------------------------------------------------------------------
  */

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

  /*
  |--------------------------------------------------------------------------
  | Delete Tenant Template
  |--------------------------------------------------------------------------
  */

  static async remove(
    id: number,
  ): Promise<void> {
    const response =
      await fetch(
        `${API_URL}/${id}`,
        {
          method:
            'DELETE',

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

  /*
  |--------------------------------------------------------------------------
  | Duplicate Tenant Template
  |--------------------------------------------------------------------------
  |
  | This is intentionally different from
  | useTemplate().
  |
  | duplicate():
  |   tenant template -> tenant copy
  |
  | useTemplate():
  |   system master -> tenant copy
  |
  */

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

    if (
      template.is_system
    ) {
      /*
       * Professional masters must use
       * the dedicated protected copy
       * endpoint.
       */

      return this.useTemplate(
        template,
        `${template.name} Copy`,
      )
    }

    const options:
      SaveFabricTemplateOptions = {
        documentType:
          template.document_type ??
          (
            template.type as
              DocumentType
          ) ??
          'certificate',

        language:
          template.language ??
          'en',

        paperSize:
          template.paper_size ??
          'custom',

        orientation:
          template.orientation ??
          'landscape',

        paperWidth:
          Number(
            template.paper_width ??
            template.canvas
              .paper?.width ??
            template.canvas
              .canvas_width ??
            1000,
          ),

        paperHeight:
          Number(
            template.paper_height ??
            template.canvas
              .paper?.height ??
            template.canvas
              .canvas_height ??
            650,
          ),

        paperUnit:
          template.paper_unit ??
          template.canvas
            .paper?.unit ??
          'px',

        schemaVersion:
          template.schema_version ??
          template.canvas
            .schema_version ??
          2,

        settings:
          template.settings ??
          null,
      }

    return this.create(
      `${template.name} Copy`,
      template.canvas,
      options,
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Set Tenant Default
  |--------------------------------------------------------------------------
  */

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
            headers(
              true,
            ),

          credentials:
            'include',

          body:
            JSON.stringify({
              default:
                true,
            }),
        },
      )

    return this.handleOne(
      response,
      'Unable to set default template.',
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Response Handler
  |--------------------------------------------------------------------------
  */

  private static async handleOne(
    response:
      Response,
    fallback:
      string,
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

  /*
  |--------------------------------------------------------------------------
  | API Error
  |--------------------------------------------------------------------------
  */

  private static async errorMessage(
    response:
      Response,
    fallback:
      string,
  ): Promise<string> {
    try {
      const data:
        {
          message?:
            string

          errors?:
            Record<
              string,
              string[]
            >
        } =
        await response.json()

      if (data.message) {
        return data.message
      }

      const firstError =
        data.errors
          ? Object.values(
              data.errors,
            )[0]?.[0]
          : undefined

      return (
        firstError ??
        fallback
      )
    } catch {
      return fallback
    }
  }
}