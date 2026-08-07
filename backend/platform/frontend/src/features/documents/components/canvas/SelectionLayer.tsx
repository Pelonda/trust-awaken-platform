import { Rect } from 'react-konva'

interface Props {
  visible: boolean
  x: number
  y: number
  width: number
  height: number
}

export default function SelectionLayer({
  visible,
  x,
  y,
  width,
  height,
}: Props) {

  if (!visible) return null

  return (
    <Rect
      x={x}
      y={y}
      width={width}
      height={height}
      fill="rgba(37,99,235,.10)"
      stroke="#2563eb"
      dash={[6, 4]}
      listening={false}
    />
  )

}