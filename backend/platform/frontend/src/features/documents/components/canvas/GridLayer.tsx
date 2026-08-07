import { Line } from 'react-konva'

interface Props {
  width: number
  height: number
  visible: boolean
  gridSize?: number
}

export default function GridLayer({
  width,
  height,
  visible,
  gridSize = 20,
}: Props) {

  if (!visible) {
    return null
  }

  const lines = []

  for (
    let x = 0;
    x <= width;
    x += gridSize
  ) {

    lines.push(

      <Line
        key={`v-${x}`}
        points={[
          x,
          0,
          x,
          height,
        ]}
        stroke="#edf2f7"
        strokeWidth={1}
      />

    )

  }

  for (
    let y = 0;
    y <= height;
    y += gridSize
  ) {

    lines.push(

      <Line
        key={`h-${y}`}
        points={[
          0,
          y,
          width,
          y,
        ]}
        stroke="#edf2f7"
        strokeWidth={1}
      />

    )

  }

  return <>{lines}</>

}