import { Text } from 'react-konva'

import { snap } from '../../utils/snap'
import { useDesignerStore } from '../../stores/designerStore'

interface Props {
  object: any
}

export default function TextObject({
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

    <Text
      text={
        object.text ??
        'New Text'
      }
      fontSize={
        object.fontSize ??
        22
      }
      fontFamily={
        object.fontFamily ??
        'Arial'
      }
      fill={
        object.color ??
        '#111827'
      }
      draggable={!object.locked}
      onClick={(e) =>
        select(
          object.id,
          e.evt.ctrlKey,
        )
      }
      onTap={() =>
        select(object.id)
      }
      onDragEnd={(e) => {

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

      }}
    />

  )

}