import Studio from './components/polotno/Studio'

interface Props {
  themeId?: string
  width?: number
  height?: number
  scale?: number
  showGrid?: boolean
}

export default function CanvasEditor(
  _props: Props,
) {

  return <Studio />

}