import {
  Transformer,
} from 'react-konva'

import {
  useEffect,
  useRef,
} from 'react'

import type Konva from 'konva'

import {
  useDesignerStore,
} from '../../../stores/designerStore'

interface Props {

  node:
    | Konva.Node
    | null

}

export default function TransformerManager({

  node,

}: Props) {

  const ref =
    useRef<Konva.Transformer>(null)

  const update =
    useDesignerStore(
      state => state.update,
    )

  useEffect(() => {

    if (
      !ref.current ||
      !node
    ) {

      return

    }

    ref.current.nodes([
      node,
    ])

    ref.current.getLayer()?.batchDraw()

  }, [node])

  if (!node) {

    return null

  }

  return (

    <Transformer

      ref={ref}

      rotateEnabled

      keepRatio={false}

      anchorSize={10}

      borderStroke="#2563eb"

      borderStrokeWidth={2}

      anchorFill="#2563eb"

      anchorStroke="#ffffff"

      enabledAnchors={[

        'top-left',

        'top-center',

        'top-right',

        'middle-left',

        'middle-right',

        'bottom-left',

        'bottom-center',

        'bottom-right',

      ]}

      onTransformEnd={() => {

        const image =
          node as Konva.Image

        const width =
          image.width() *
          image.scaleX()

        const height =
          image.height() *
          image.scaleY()

        image.scaleX(1)

        image.scaleY(1)

        update(

          image.id(),

          {

            x: image.x(),

            y: image.y(),

            width,

            height,

            rotation:
              image.rotation(),

          },

        )

      }}

    />

  )

}