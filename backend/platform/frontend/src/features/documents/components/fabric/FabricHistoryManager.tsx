import {
  IconButton,
  Stack,
  Tooltip,
} from '@mui/material'

import UndoIcon from '@mui/icons-material/Undo'
import RedoIcon from '@mui/icons-material/Redo'

import {
  useCallback,
  useEffect,
  useRef,
  useState,
} from 'react'

import type {
  Canvas,
  FabricObject,
} from 'fabric'

import {
  isProtectedObject,
} from './FabricProtectedFields'

interface Props {
  canvas: Canvas | null
}

interface Snapshot {
  json: string
}

const MAX_HISTORY = 50

const CUSTOM_PROPERTIES = [
  'awakenType',
  'awakenVariable',
  'awakenProtected',
  'awakenBrandAsset',
  'awakenAssetUuid',
  'awakenAssetPath',
]

export default function FabricHistoryManager({
  canvas,
}: Props) {
  const undoStack =
    useRef<Snapshot[]>([])

  const redoStack =
    useRef<Snapshot[]>([])

  const clipboard =
    useRef<FabricObject | null>(
      null,
    )

  const restoring =
    useRef(false)

  const [
    canUndo,
    setCanUndo,
  ] = useState(false)

  const [
    canRedo,
    setCanRedo,
  ] = useState(false)

  const updateButtons =
    useCallback(() => {
      setCanUndo(
        undoStack.current.length >
          1,
      )

      setCanRedo(
        redoStack.current.length >
          0,
      )
    }, [])

  const snapshot =
    useCallback(() => {
      if (
        !canvas ||
        restoring.current
      ) {
        return
      }

      /*
       * Keep Trust AWAKEN metadata
       * inside undo/redo snapshots.
       */
      const json =
        JSON.stringify(
          canvas.toJSON(
            CUSTOM_PROPERTIES,
          ),
        )

      const stack =
        undoStack.current

      if (
        stack.length > 0 &&
        stack[
          stack.length - 1
        ].json === json
      ) {
        return
      }

      stack.push({
        json,
      })

      if (
        stack.length >
        MAX_HISTORY
      ) {
        stack.shift()
      }

      redoStack.current = []

      updateButtons()
    }, [
      canvas,
      updateButtons,
    ])

  const restore =
    useCallback(
      async (
        state: Snapshot,
      ) => {
        if (!canvas) {
          return
        }

        restoring.current =
          true

        try {
          canvas.discardActiveObject()

          canvas.clear()

          await canvas.loadFromJSON(
            JSON.parse(
              state.json,
            ),
          )

          /*
           * Restore visual controls
           * after loading history.
           */
          canvas
            .getObjects()
            .forEach(
              object => {
                const protectedObject =
                  isProtectedObject(
                    object,
                  )

                object.set({
                  cornerColor:
                    protectedObject
                      ? '#7c3aed'
                      : '#2563eb',

                  cornerStrokeColor:
                    '#ffffff',

                  borderColor:
                    protectedObject
                      ? '#7c3aed'
                      : '#2563eb',

                  cornerSize: 12,

                  transparentCorners:
                    false,

                  borderScaleFactor:
                    1.5,

                  padding: 2,
                })

                object.setCoords()
              },
            )

          canvas.requestRenderAll()
        } finally {
          restoring.current =
            false
        }
      },
      [canvas],
    )

  const undo =
    useCallback(
      async () => {
        if (
          !canvas ||
          undoStack.current
            .length <= 1
        ) {
          return
        }

        const current =
          undoStack.current.pop()

        if (current) {
          redoStack.current.push(
            current,
          )
        }

        const previous =
          undoStack.current[
            undoStack.current
              .length - 1
          ]

        if (previous) {
          await restore(
            previous,
          )
        }

        updateButtons()
      },
      [
        canvas,
        restore,
        updateButtons,
      ],
    )

  const redo =
    useCallback(
      async () => {
        if (
          !canvas ||
          redoStack.current
            .length === 0
        ) {
          return
        }

        const next =
          redoStack.current.pop()

        if (!next) {
          return
        }

        undoStack.current.push(
          next,
        )

        await restore(
          next,
        )

        updateButtons()
      },
      [
        canvas,
        restore,
        updateButtons,
      ],
    )

  const copy =
    useCallback(
      async () => {
        if (!canvas) {
          return
        }

        const active =
          canvas.getActiveObject()

        if (!active) {
          return
        }

        /*
         * Credential ID and QR may
         * not be copied/duplicated.
         */
        if (
          isProtectedObject(
            active,
          )
        ) {
          return
        }

        clipboard.current =
          await active.clone()
      },
      [canvas],
    )

  const paste =
    useCallback(
      async () => {
        if (
          !canvas ||
          !clipboard.current
        ) {
          return
        }

        /*
         * Extra safety in case an
         * old protected object somehow
         * exists in the clipboard.
         */
        if (
          isProtectedObject(
            clipboard.current,
          )
        ) {
          clipboard.current =
            null

          return
        }

        const clone =
          await clipboard.current.clone()

        clone.set({
          left:
            (clone.left ?? 0) +
            20,

          top:
            (clone.top ?? 0) +
            20,

          evented: true,
        })

        clone.setCoords()

        canvas.discardActiveObject()

        canvas.add(
          clone,
        )

        canvas.setActiveObject(
          clone,
        )

        canvas.requestRenderAll()

        clipboard.current =
          await clone.clone()
      },
      [canvas],
    )

  /*
   * Protected does NOT mean that the
   * template designer cannot remove
   * the field.
   *
   * Credential ID and Verification QR
   * may be deleted from the template.
   *
   * Their protected DATA SOURCE is what
   * the designer cannot redefine.
   */
  const removeSelected =
    useCallback(() => {
      if (!canvas) {
        return
      }

      const active =
        canvas.getActiveObjects()

      if (
        active.length === 0
      ) {
        return
      }

      canvas.discardActiveObject()

      active.forEach(
        object => {
          canvas.remove(
            object,
          )
        },
      )

      canvas.requestRenderAll()
    }, [canvas])

  useEffect(() => {
    if (!canvas) {
      return
    }

    undoStack.current = []
    redoStack.current = []

    snapshot()

    const historyEvents = [
      'object:added',
      'object:removed',
      'object:modified',
    ] as const

    historyEvents.forEach(
      event => {
        canvas.on(
          event,
          snapshot,
        )
      },
    )

    return () => {
      historyEvents.forEach(
        event => {
          canvas.off(
            event,
            snapshot,
          )
        },
      )
    }
  }, [
    canvas,
    snapshot,
  ])

  useEffect(() => {
    if (!canvas) {
      return
    }

    function keyDown(
      event: KeyboardEvent,
    ) {
      const target =
        event.target as
          HTMLElement | null

      const typing =
        target?.tagName ===
          'INPUT' ||
        target?.tagName ===
          'TEXTAREA' ||
        target?.isContentEditable

      if (typing) {
        return
      }

      const modifier =
        event.ctrlKey ||
        event.metaKey

      /*
       * Undo
       *
       * Windows/Linux: Ctrl+Z
       * macOS: Command+Z
       */
      if (
        modifier &&
        event.key.toLowerCase() ===
          'z'
      ) {
        event.preventDefault()

        if (event.shiftKey) {
          void redo()
        } else {
          void undo()
        }

        return
      }

      /*
       * Redo
       *
       * Windows/Linux: Ctrl+Y
       */
      if (
        modifier &&
        event.key.toLowerCase() ===
          'y'
      ) {
        event.preventDefault()

        void redo()

        return
      }

      /*
       * Copy
       */
      if (
        modifier &&
        event.key.toLowerCase() ===
          'c'
      ) {
        event.preventDefault()

        void copy()

        return
      }

      /*
       * Paste
       */
      if (
        modifier &&
        event.key.toLowerCase() ===
          'v'
      ) {
        event.preventDefault()

        void paste()

        return
      }

      /*
       * Delete is allowed even for
       * Credential ID and QR.
       */
      if (
        event.key ===
          'Delete' ||
        event.key ===
          'Backspace'
      ) {
        event.preventDefault()

        removeSelected()
      }
    }

    window.addEventListener(
      'keydown',
      keyDown,
    )

    return () => {
      window.removeEventListener(
        'keydown',
        keyDown,
      )
    }
  }, [
    canvas,
    copy,
    paste,
    redo,
    removeSelected,
    undo,
  ])

  return (
    <Stack
      direction="row"
      spacing={0.25}
      alignItems="center"
    >
      <Tooltip title="Undo (Ctrl+Z)">
        <span>
          <IconButton
            size="small"
            disabled={!canUndo}
            onClick={() =>
              void undo()
            }
          >
            <UndoIcon />
          </IconButton>
        </span>
      </Tooltip>

      <Tooltip title="Redo (Ctrl+Y)">
        <span>
          <IconButton
            size="small"
            disabled={!canRedo}
            onClick={() =>
              void redo()
            }
          >
            <RedoIcon />
          </IconButton>
        </span>
      </Tooltip>
    </Stack>
  )
}