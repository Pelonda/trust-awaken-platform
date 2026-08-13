import type {
  Canvas,
} from 'fabric'

import type {
  FabricPageJSON,
} from '../../services/FabricTemplateService'

export type FabricPageKey =
  | 'front'
  | 'back'

export interface FabricPageState {
  activePage:
    FabricPageKey

  pages:
    Record<
      FabricPageKey,
      FabricPageJSON
    >
}

const CUSTOM_PROPERTIES = [
  'awakenType',
  'awakenVariable',
  'awakenProtected',
  'awakenBrandAsset',
  'awakenAssetUuid',
  'awakenAssetPath',
  'awakenQrCodePath',
  'awakenVerificationUrl',
  'awakenVerificationCode',
] as const

/*
|--------------------------------------------------------------------------
| Blank Page
|--------------------------------------------------------------------------
*/

export function createBlankFabricPage(
  key: FabricPageKey,
  width: number,
  height: number,
): FabricPageJSON {
  return {
    key,

    canvas_width:
      width,

    canvas_height:
      height,

    background:
      '#ffffff',

    objects:
      [],
  }
}

/*
|--------------------------------------------------------------------------
| Serialize Current Canvas
|--------------------------------------------------------------------------
*/

export function serializeFabricPage(
  canvas: Canvas,
  key: FabricPageKey,
): FabricPageJSON {
  const json =
    canvas.toJSON(
      [
        ...CUSTOM_PROPERTIES,
      ],
    ) as {
      version?: string
      objects?: unknown[]
      background?: string
    }

  return {
    key,

    canvas_width:
      canvas.getWidth(),

    canvas_height:
      canvas.getHeight(),

    version:
      json.version,

    background:
      typeof json.background ===
      'string'
        ? json.background
        : '#ffffff',

    objects:
      Array.isArray(
        json.objects,
      )
        ? json.objects
        : [],
  }
}

/*
|--------------------------------------------------------------------------
| Load Page
|--------------------------------------------------------------------------
*/

export async function loadFabricPage(
  canvas: Canvas,
  page: FabricPageJSON,
): Promise<void> {
  canvas.discardActiveObject()

  canvas.clear()

  canvas.setDimensions({
    width:
      page.canvas_width,

    height:
      page.canvas_height,
  })

  canvas.backgroundColor =
    page.background ??
    '#ffffff'

  await canvas.loadFromJSON({
    version:
      page.version,

    objects:
      page.objects,

    background:
      page.background ??
      '#ffffff',
  })

  canvas.calcOffset()

  canvas.requestRenderAll()
}

/*
|--------------------------------------------------------------------------
| Create Initial State
|--------------------------------------------------------------------------
*/

export function createFabricPageState(
  width: number,
  height: number,
): FabricPageState {
  return {
    activePage:
      'front',

    pages: {
      front:
        createBlankFabricPage(
          'front',
          width,
          height,
        ),

      back:
        createBlankFabricPage(
          'back',
          width,
          height,
        ),
    },
  }
}

/*
|--------------------------------------------------------------------------
| Resize Blank Page
|--------------------------------------------------------------------------
|
| This is useful when Document Setup changes from A4 to CR80, etc.
|
| We intentionally don't scale existing objects here. Resizing a populated
| design and scaling its objects should be an explicit operation later.
|
*/

export function resizeFabricPage(
  page: FabricPageJSON,
  width: number,
  height: number,
): FabricPageJSON {
  return {
    ...page,

    canvas_width:
      width,

    canvas_height:
      height,
  }
}