import { Line } from 'react-konva'

interface Props {
  vertical?: number
  horizontal?: number
}

export default function SnapGuides({
  vertical,
  horizontal,
}: Props) {

  return (
    <>

      {vertical !== undefined && (

        <Line
          points={[
            vertical,
            0,
            vertical,
            5000,
          ]}
          stroke="#ef4444"
          dash={[4, 4]}
          listening={false}
        />

      )}

      {horizontal !== undefined && (

        <Line
          points={[
            0,
            horizontal,
            5000,
            horizontal,
          ]}
          stroke="#ef4444"
          dash={[4, 4]}
          listening={false}
        />

      )}

    </>
  )

}