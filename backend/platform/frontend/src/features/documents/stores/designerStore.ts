import { create } from 'zustand'
import type { DocumentAsset } from '../models/DocumentAsset'

export interface DesignerObject {

  id: string

  type: string

  x: number

  y: number

  width?: number

  height?: number

  rotation?: number

  opacity?: number

  text?: string

  visible?: boolean

  locked?: boolean

  asset?: DocumentAsset

}

interface Snapshot {

  objects: DesignerObject[]

}

interface DesignerState {

  objects: DesignerObject[]

  history: Snapshot[]

  future: Snapshot[]

  clipboard: DesignerObject | null

  selectedId: string | null

  selectedIds: string[]

  add(object: DesignerObject): void

  update(
    id: string,
    values: Partial<DesignerObject>,
  ): void

  remove(id: string): void

  select(
    id: string | null,
    append?: boolean,
  ): void

  clearSelection(): void

  copy(): void

  paste(): void

  undo(): void

  redo(): void

}

export const useDesignerStore =
create<DesignerState>((set, get) => ({

  objects: [],

  history: [],

  future: [],

  clipboard: null,

  selectedId: null,

  selectedIds: [],

  add(object) {

    const snapshot = structuredClone({

      objects: get().objects,

    })

    set({

      history: [

        ...get().history,

        snapshot,

      ],

      future: [],

      objects: [

        ...get().objects,

        object,

      ],

      selectedId: object.id,

      selectedIds: [

        object.id,

      ],

    })

  },

  update(id, values) {

    const snapshot = structuredClone({

      objects: get().objects,

    })

    set({

      history: [

        ...get().history,

        snapshot,

      ],

      future: [],

      objects:

        get().objects.map(

          object =>

            object.id === id

              ? {

                  ...object,

                  ...values,

                }

              : object,

        ),

    })

  },

  remove(id) {

    const snapshot = structuredClone({

      objects: get().objects,

    })

    set({

      history: [

        ...get().history,

        snapshot,

      ],

      future: [],

      selectedId: null,

      selectedIds: [],

      objects:

        get().objects.filter(

          object =>

            object.id !== id,

        ),

    })

  },

  select(id, append = false) {

    if (!id) {

      set({

        selectedId: null,

        selectedIds: [],

      })

      return

    }

    if (append) {

      const ids =

        get().selectedIds

      if (!ids.includes(id)) {

        set({

          selectedId: id,

          selectedIds: [

            ...ids,

            id,

          ],

        })

      }

      return

    }

    set({

      selectedId: id,

      selectedIds: [

        id,

      ],

    })

  },

  selected(){

  return get().objects.find(

    object=>

      object.id===

      get().selectedId,

  )

},

  clearSelection() {

    set({

      selectedId: null,

      selectedIds: [],

    })

  },

  copy() {

    const selected =

      get().objects.find(

        object =>

          object.id ===

          get().selectedId,

      )

    if (!selected) return

    set({

      clipboard:

        structuredClone(selected),

    })

  },

  paste() {

    const clipboard =

      get().clipboard

    if (!clipboard) return

    const snapshot = structuredClone({

      objects: get().objects,

    })

    const copy = {

      ...structuredClone(

        clipboard,

      ),

      id: crypto.randomUUID(),

      x: clipboard.x + 20,

      y: clipboard.y + 20,

    }

    set({

      history: [

        ...get().history,

        snapshot,

      ],

      future: [],

      objects: [

        ...get().objects,

        copy,

      ],

      selectedId: copy.id,

      selectedIds: [

        copy.id,

      ],

    })

  },

  undo() {

    const history =

      get().history

    if (!history.length) return

    const previous =

      history.at(-1)!

    set({

      future: [

        {

          objects:

            structuredClone(

              get().objects,

            ),

        },

        ...get().future,

      ],

      history:

        history.slice(0, -1),

      objects:

        structuredClone(

          previous.objects,

        ),

    })

  },

  redo() {

    const future =

      get().future

    if (!future.length) return

    const next =

      future[0]

    set({

      history: [

        ...get().history,

        {

          objects:

            structuredClone(

              get().objects,

            ),

        },

      ],

      future:

        future.slice(1),

      objects:

        structuredClone(

          next.objects,

        ),

    })

  },

}))