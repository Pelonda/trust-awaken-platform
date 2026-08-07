import { Text } from 'react-konva'

import { snap } from '../../utils/snap'
import { useDesignerStore } from '../../stores/designerStore'

interface Props {
  object: any
}

export default function VariableObject({
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
        object.variable ??
        '{{variable}}'
      }
      fill="#0f4c81"
      fontStyle="italic"
      fontSize={20}
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
            x: snap(e.target.x()),
            y: snap(e.target.y()),
          },
        )
      }
    />

  )

}