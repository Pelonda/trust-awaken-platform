import {
  Layer,
  Stage,
} from 'react-konva'

import {
  useRef,
  useState,
} from 'react'

import type Konva from 'konva'

import GridLayer from './GridLayer'
import PageLayer from './PageLayer'

import ObjectLayer from './engine/ObjectLayer'
import TransformerManager from './engine/TransformerManager'
import GuideLayer from './engine/GuideLayer'
import { useDesignerStore } from '../../stores/designerStore'

interface Props {

  themeId: string

  width: number

  height: number

  scale: number

  showGrid: boolean

}

export default function DesignerCanvas({

  themeId,

  width,

  height,

  scale,

  showGrid,

}: Props) {

  const stageRef =
    useRef<Konva.Stage>(null)

    const update =
  useDesignerStore(
    state => state.update,
  )

  const [

    selectedNode,

    setSelectedNode,

  ] =
    useState<Konva.Group | null>(
      null,
    )

  return (

    <Stage

      ref={stageRef}

      width={1200}

      height={800}

      scaleX={scale}

      scaleY={scale}

      draggable

      style={{

        background:

          themeId ===
          'global-cybersafe'

            ? '#dbe4ef'

            : '#eef2f7',

      }}

    >

      <Layer>

        <GridLayer

          width={width}

          height={height}

          visible={showGrid}

        />

        <PageLayer

          width={width}

          height={height}

        />

        <ObjectLayer

          onSelect={

            setSelectedNode

          }

        />

        <TransformerManager

  node={selectedNode}

  onTransformEnd={(node) => {

    const scaleX =
      node.scaleX()

    const scaleY =
      node.scaleY()

    node.scaleX(1)

    node.scaleY(1)

    update(

      node.id(),

      {

        x: node.x(),

        y: node.y(),

        width: Math.max(
          30,
          node.width() * scaleX,
        ),

        height: Math.max(
          30,
          node.height() * scaleY,
        ),

        rotation:
          node.rotation(),

      },

    )

  }}

/>

        <GuideLayer />

      </Layer>

    </Stage>

  )

}