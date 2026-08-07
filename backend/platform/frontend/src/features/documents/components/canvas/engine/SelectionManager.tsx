import type Konva from 'konva'

interface Props {

  selectedNode: Konva.Node | null

  onChange(
    node: Konva.Node | null,
  ): void

}

export default function SelectionManager({

  selectedNode,

}: Props) {

  return null

}