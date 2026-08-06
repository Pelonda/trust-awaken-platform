import {
  Layer,
  Rect,
  Stage,
  Text,
  Transformer,
  Image,
} from 'react-konva'

import useImage from 'use-image'

import {
  useEffect,
  useRef,
  useState,
} from 'react'

import type Konva from 'konva'

function Logo() {

  const [image] = useImage(
    '/logo.png'
  )

  if (!image) return null

  return (
    <Image
      image={image}
      x={40}
      y={40}
      width={120}
      height={120}
      draggable
    />
  )

}

export default function CanvasEditor() {

  const transformerRef =
    useRef<Konva.Transformer>(null)

  const textRef =
    useRef<Konva.Text>(null)

  const [selected, setSelected] =
    useState(false)

  useEffect(() => {

    if (
      selected &&
      transformerRef.current &&
      textRef.current
    ) {

      transformerRef.current.nodes([
        textRef.current,
      ])

      transformerRef.current
        .getLayer()
        ?.batchDraw()

    }

  }, [selected])

  return (

    <Stage
      width={900}
      height={650}
      style={{
        background: '#e5e7eb',
        border: '1px solid #cbd5e1',
      }}
      onMouseDown={(e) => {

        if (
          e.target === e.target.getStage()
        ) {

          setSelected(false)

        }

      }}
    >

      <Layer>

        <Rect
          x={20}
          y={20}
          width={860}
          height={610}
          fill="white"
          shadowBlur={10}
          cornerRadius={4}
        />

        <Logo />

        <Text
          ref={textRef}
          text="Certificate of Achievement"
          x={220}
          y={60}
          fontSize={30}
          fontStyle="bold"
          draggable
          onClick={() =>
            setSelected(true)
          }
          onTap={() =>
            setSelected(true)
          }
        />

        <Text
          text="{{participant.name}}"
          x={230}
          y={160}
          fontSize={28}
          fill="#2563eb"
          draggable
        />

        <Text
          text="{{program.title}}"
          x={210}
          y={250}
          fontSize={20}
          draggable
        />

        {selected && (

          <Transformer
            ref={transformerRef}
            rotateEnabled
          />

        )}

      </Layer>

    </Stage>

  )

}