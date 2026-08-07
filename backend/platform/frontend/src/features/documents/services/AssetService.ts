import type {
  DocumentAsset,
} from '../models/DocumentAsset'

export default class AssetService {

  static create(
    file: File,
  ): Promise<DocumentAsset> {

    return new Promise((resolve) => {

      const image =
        new Image()

      const url =
        URL.createObjectURL(file)

      image.onload = () => {

        resolve({

          id:
            crypto.randomUUID(),

          name:
            file.name,

          type:
            'image',

          url,

          width:
            image.width,

          height:
            image.height,

          createdAt:
            new Date()
              .toISOString(),

        })

      }

      image.src = url

    })

  }

}