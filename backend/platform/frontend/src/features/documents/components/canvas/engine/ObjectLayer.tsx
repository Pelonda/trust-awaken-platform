import CanvasNode from './CanvasNode'

import { useDesignerStore } from '../../../stores/designerStore'

import type Konva from 'konva'

interface Props {

  onSelect(
    node: Konva.Group,
  ): void

}

export default function ObjectLayer({

  onSelect,

}: Props) {

  const objects =
    useDesignerStore(
      state => state.objects,
    )

  const selectedIds =
    useDesignerStore(
      state => state.selectedIds,
    )

  const update =
    useDesignerStore(
      state => state.update,
    )

  const select =
    useDesignerStore(
      state => state.select,
    )

  return (

    <>

      {objects.map(object => (

        <CanvasNode

          key={object.id}

          object={object}

          selected={
            selectedIds.includes(
              object.id,
            )
          }

          onSelect={(
            node,
            id,
            append,
          ) => {

            select(
              id,
              append,
            )

            onSelect(node)

          }}

          onMove={(
            id,
            x,
            y,
          ) => {

            update(
              id,
              {
                x,
                y,
              },
            )

          }}

        />

      ))}

    </>

  )

}