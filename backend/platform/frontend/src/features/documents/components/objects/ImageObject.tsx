import {
  Image,
} from 'react-konva'

import useImage from 'use-image'

import {
  forwardRef,
} from 'react'

import type Konva from 'konva'

interface Props {
  object:any
}

const ImageObject = forwardRef<
Konva.Image,
Props
>(({

  object,

},ref)=>{

  const [image]=
    useImage(
      object.asset?.url ?? '',
    )

  if(!image){
    return null
  }

  return(

    <Image

      ref={ref}

      x={0}

      y={0}

      image={image}

      width={
        object.width
      }

      height={
        object.height
      }

      opacity={
        object.opacity ?? 1
      }

      rotation={0}

    />

  )

})

ImageObject.displayName='ImageObject'

export default ImageObject