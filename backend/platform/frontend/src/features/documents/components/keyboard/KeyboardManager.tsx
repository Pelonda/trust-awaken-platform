import { useEffect } from 'react'

import { useDesignerStore } from '../../stores/designerStore'

export default function KeyboardManager() {

  const selectedId =
    useDesignerStore(
      state => state.selectedId,
    )

  const objects =
    useDesignerStore(
      state => state.objects,
    )

  const update =
    useDesignerStore(
      state => state.update,
    )

  const remove =
    useDesignerStore(
      state => state.remove,
    )

  const copy =
    useDesignerStore(
      state => state.copy,
    )

  const paste =
    useDesignerStore(
      state => state.paste,
    )

  const undo =
    useDesignerStore(
      state => state.undo,
    )

  const redo =
    useDesignerStore(
      state => state.redo,
    )

  useEffect(() => {

    function onKeyDown(
      e: KeyboardEvent,
    ) {

      if (
        e.target instanceof HTMLInputElement ||
        e.target instanceof HTMLTextAreaElement
      ) {
        return
      }

      if (
        e.ctrlKey &&
        e.key.toLowerCase() === 'z'
      ) {
        e.preventDefault()
        undo()
        return
      }

      if (
        e.ctrlKey &&
        e.key.toLowerCase() === 'y'
      ) {
        e.preventDefault()
        redo()
        return
      }

      if (
        e.ctrlKey &&
        e.key.toLowerCase() === 'c'
      ) {
        e.preventDefault()
        copy()
        return
      }

      if (
        e.ctrlKey &&
        e.key.toLowerCase() === 'v'
      ) {
        e.preventDefault()
        paste()
        return
      }

      if (
        e.ctrlKey &&
        e.key.toLowerCase() === 'd'
      ) {

        e.preventDefault()

        copy()

        setTimeout(
          paste,
          0,
        )

        return

      }

      if (
        e.key === 'Delete' &&
        selectedId
      ) {

        remove(selectedId)

        return

      }

      if (!selectedId) {
        return
      }

      const object =
        objects.find(
          o =>
            o.id === selectedId,
        )

      if (!object) {
        return
      }

      const STEP =
        e.shiftKey ? 10 : 1

      switch (e.key) {

        case 'ArrowLeft':

          update(
            object.id,
            {
              x: object.x - STEP,
            },
          )

          break

        case 'ArrowRight':

          update(
            object.id,
            {
              x: object.x + STEP,
            },
          )

          break

        case 'ArrowUp':

          update(
            object.id,
            {
              y: object.y - STEP,
            },
          )

          break

        case 'ArrowDown':

          update(
            object.id,
            {
              y: object.y + STEP,
            },
          )

          break

      }

    }

    window.addEventListener(
      'keydown',
      onKeyDown,
    )

    return () =>
      window.removeEventListener(
        'keydown',
        onKeyDown,
      )

  }, [
    selectedId,
    objects,
    update,
    remove,
    copy,
    paste,
    undo,
    redo,
  ])

  return null

}