import { Rect } from 'react-konva'

import { snap } from '../../utils/snap'
import { useDesignerStore } from '../../stores/designerStore'

interface Props {
  object: any
}

export default function RectangleObject({
  object,
}: Props) {

  const update =
    useDesignerStore(
      state => state.update,
    )

  const select =
    useDesignerStore(
      state => state.select,
    )

  return (

    <Rect

      width={
        object.width ??
        120
      }

      height={
        object.height ??
        60
      }

      fill="#2563eb22"

      stroke="#2563eb"

      draggable={!object.locked}

      onClick={(e) =>
        select(
          object.id,
          e.evt.ctrlKey,
        )
      }

      onDragEnd={(e) =>

        update(
          object.id,
          {

            x: snap(
              e.target.x(),
            ),

            y: snap(
              e.target.y(),
            ),

          },

        )

      }

    />

  )

}