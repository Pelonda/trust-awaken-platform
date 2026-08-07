import {
  Box,
  Card,
  CardActionArea,
  CardContent,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import { useState } from 'react'

import ImageUploader from './components/assets/ImageUploader'

import AssetService
from './services/AssetService'

import ImageService
from './services/ImageService'

import useDesigner
from './hooks/useDesigner'

import type {
  DocumentAsset,
} from './models/DocumentAsset'

export default function AssetLibrary() {

  const { add } =
    useDesigner()

  const [assets, setAssets] =
    useState<DocumentAsset[]>([])

  async function upload(
    file: File,
  ) {

    const asset =
      await AssetService.create(
        file,
      )

    setAssets(
      current => [

        ...current,

        asset,

      ],
    )

  }

  function place(
    asset: DocumentAsset,
  ) {

    add(

      ImageService.toCanvasObject(
        asset,
      ),

    )

  }

  return (

    <Stack spacing={2}>

      <Typography
        variant="h6"
        fontWeight={700}
      >
        Assets
      </Typography>

      <ImageUploader
        onUpload={upload}
      />

      <Divider />

      {assets.map(asset => (

        <Card
          key={asset.id}
          variant="outlined"
        >

          <CardActionArea
            onClick={() =>
              place(asset)
            }
          >

            <Box
              component="img"
              src={asset.url}
              sx={{
                width: '100%',
                height: 120,
                objectFit: 'contain',
                bgcolor: '#f8fafc',
              }}
            />

            <CardContent>

              <Typography
                variant="body2"
                noWrap
              >
                {asset.name}
              </Typography>

            </CardContent>

          </CardActionArea>

        </Card>

      ))}

    </Stack>

  )

}