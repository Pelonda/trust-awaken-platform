import { Rect } from 'react-konva'

interface Props {
  width: number
  height: number
}

export default function PageLayer({
  width,
  height,
}: Props) {

  return (

    <Rect
      x={40}
      y={40}
      width={width - 80}
      height={height - 80}
      fill="#ffffff"
      stroke="#d1d5db"
      strokeWidth={1}
      shadowColor="#000"
      shadowBlur={12}
      shadowOpacity={0.12}
      shadowOffset={{
        x: 0,
        y: 4,
      }}
      cornerRadius={6}
      listening={false}
    />

  )

}