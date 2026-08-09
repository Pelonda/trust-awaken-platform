import type {
  FabricObject,
} from 'fabric'

export interface AwakenFabricObject
  extends FabricObject {
  awakenType?: string

  awakenVariable?: string

  awakenProtected?: boolean
}

export function isProtectedObject(
  object:
    FabricObject | null | undefined,
): boolean {
  if (!object) {
    return false
  }

  return Boolean(
    (
      object as
        AwakenFabricObject
    ).awakenProtected,
  )
}

export function applyProtectedStyle(
  object: FabricObject,
) {
  if (
    !isProtectedObject(
      object,
    )
  ) {
    return
  }

  object.set({
    borderColor:
      '#7c3aed',

    cornerColor:
      '#7c3aed',

    cornerStrokeColor:
      '#ffffff',

    transparentCorners:
      false,
  })

  object.setCoords()
}