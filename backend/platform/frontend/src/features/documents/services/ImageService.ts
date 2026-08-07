import type { DocumentAsset } from '../models/DocumentAsset'

const MAX_WIDTH = 240
const MAX_HEIGHT = 180

export default class ImageService {

  static fit(
    width: number,
    height: number,
  ) {

    const ratio = Math.min(
      MAX_WIDTH / width,
      MAX_HEIGHT / height,
      1,
    )

    return {

      width: Math.round(
        width * ratio,
      ),

      height: Math.round(
        height * ratio,
      ),

    }

  }

  static toCanvasObject(
    asset: DocumentAsset,
  ) {

    const size =
      this.fit(
        asset.width,
        asset.height,
      )

    return {

      id:
        crypto.randomUUID(),

      type: 'image',

      x: 160,

      y: 160,

      width:
        size.width,

      height:
        size.height,

      rotation: 0,

      opacity: 1,

      visible: true,

      locked: false,

      asset,

    }

  }

  static resize(
    width: number,
    height: number,
  ) {

    return this.fit(
      width,
      height,
    )

  }

  static replace(
    object: any,
    asset: DocumentAsset,
  ) {

    const size =
      this.fit(
        asset.width,
        asset.height,
      )

    return {

      ...object,

      asset,

      width:
        size.width,

      height:
        size.height,

    }

  }

  static rotate(
    object: any,
    rotation: number,
  ) {

    return {

      ...object,

      rotation,

    }

  }

  static opacity(
    object: any,
    opacity: number,
  ) {

    return {

      ...object,

      opacity,

    }

  }

  static setSize(
  object:any,
  width:number,
  height:number,
){

  return{

    ...object,

    width,

    height,

  }

}

static setOpacity(
  object:any,
  opacity:number,
){

  return{

    ...object,

    opacity,

  }

}

static setRotation(
  object:any,
  rotation:number,
){

  return{

    ...object,

    rotation,

  }

}

}