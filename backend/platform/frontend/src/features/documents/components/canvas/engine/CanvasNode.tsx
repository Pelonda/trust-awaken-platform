import { Group } from 'react-konva'
import { useRef } from 'react'

import type Konva from 'konva'

import ImageObject from '../../objects/ImageObject'
import LogoObject from '../../objects/LogoObject'
import BackgroundObject from '../../objects/BackgroundObject'
import WatermarkObject from '../../objects/WatermarkObject'
import SignatureObject from '../../objects/SignatureObject'
import SealObject from '../../objects/SealObject'
import RectangleObject from '../../objects/RectangleObject'
import TextObject from '../../objects/TextObject'
import VariableObject from '../../objects/VariableObject'
import BarcodeObject from '../../objects/BarcodeObject'
import QrObject from '../../objects/QrObject'

interface Props {

  object:any

  selected:boolean

  onSelect(
    node:Konva.Group,
    id:string,
    append:boolean,
  ):void

  onMove(
    id:string,
    x:number,
    y:number,
  ):void

}

export default function CanvasNode({

  object,

  selected,

  onSelect,

  onMove,

}:Props){

  const ref =
    useRef<Konva.Group>(null)

  function render(){

    switch(object.type){

      case 'text':
        return <TextObject object={object} />

      case 'rectangle':
        return <RectangleObject object={object} />

      case 'image':
        return <ImageObject object={object} />

      case 'logo':
        return <LogoObject object={object} />

      case 'background':
        return <BackgroundObject object={object} />

      case 'watermark':
        return <WatermarkObject object={object} />

      case 'signature':
        return <SignatureObject object={object} />

      case 'seal':
        return <SealObject object={object} />

      case 'variable':
        return <VariableObject object={object} />

      case 'barcode':
        return <BarcodeObject object={object} />

      case 'qrcode':
        return <QrObject object={object} />

      default:
        return null

    }

  }

  return (

    <Group

      ref={ref}

      x={object.x}

      y={object.y}

      rotation={object.rotation ?? 0}

      opacity={object.opacity ?? 1}

      visible={object.visible ?? true}

      draggable={!object.locked}

      onClick={(e)=>{

        if(!ref.current)return

        onSelect(

          ref.current,

          object.id,

          e.evt.ctrlKey ||

          e.evt.metaKey,

        )

      }}

      onTap={()=>{

        if(!ref.current)return

        onSelect(

          ref.current,

          object.id,

          false,

        )

      }}

      onDragEnd={(e)=>{

        onMove(

          object.id,

          e.target.x(),

          e.target.y(),

        )

      }}

    >

      {render()}

    </Group>

  )

}