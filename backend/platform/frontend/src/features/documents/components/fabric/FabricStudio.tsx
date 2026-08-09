import {
  Box,
  Button,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import AddIcon from '@mui/icons-material/Add'
import CropSquareIcon from '@mui/icons-material/CropSquare'
import ImageIcon from '@mui/icons-material/Image'

import {
  useEffect,
  useRef,
  useState,
  type ChangeEvent,
} from 'react'

import {
  Canvas,
  FabricImage,
  IText,
  Rect,
  type FabricObject,
} from 'fabric'

import AssetService from '../../services/AssetService'

import FabricBrandPanel from './FabricBrandPanel'
import FabricHistoryManager from './FabricHistoryManager'
import FabricLayersPanel from './FabricLayersPanel'
import FabricObjectToolbar from './FabricObjectToolbar'
import FabricQrPanel from './FabricQrPanel'
import FabricTemplateManager from './FabricTemplateManager'
import FabricTextToolbar from './FabricTextToolbar'
import FabricVariablesPanel from './FabricVariablesPanel'

interface AwakenFabricObject
  extends FabricObject {
  awakenProtected?: boolean
  awakenType?: string
  awakenVariable?: string
  awakenBrandAsset?: string
  awakenAssetUuid?: string
  awakenAssetPath?: string
}

export default function FabricStudio() {
  const canvasElementRef =
    useRef<HTMLCanvasElement | null>(
      null,
    )

  const canvasRef =
    useRef<Canvas | null>(
      null,
    )

  const fileInputRef =
    useRef<HTMLInputElement | null>(
      null,
    )

  const [
    canvasReady,
    setCanvasReady,
  ] = useState(false)

  useEffect(() => {
    if (!canvasElementRef.current) {
      return
    }

    const canvas =
      new Canvas(
        canvasElementRef.current,
        {
          width: 1000,
          height: 650,

          backgroundColor:
            '#ffffff',

          preserveObjectStacking:
            true,

          selection:
            true,
        },
      )

    canvasRef.current =
      canvas

    configureCanvas(
      canvas,
    )

    createBlankCanvas(
      canvas,
    )

    setCanvasReady(
      true,
    )

    return () => {
      setCanvasReady(
        false,
      )

      canvas.dispose()

      canvasRef.current =
        null
    }
  }, [])

  function configureCanvas(
    canvas: Canvas,
  ) {
    canvas.selectionColor =
      'rgba(37, 99, 235, 0.08)'

    canvas.selectionBorderColor =
      '#2563eb'

    canvas.selectionLineWidth =
      1
  }

  function createBlankCanvas(
    canvas: Canvas,
  ) {
    canvas.discardActiveObject()

    canvas.clear()

    canvas.backgroundColor =
      '#ffffff'

    canvas.requestRenderAll()
  }

  function newTemplate() {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    createBlankCanvas(
      canvas,
    )
  }

  function addText() {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    const text =
      new IText(
        'New Text',
        {
          left: 200,
          top: 200,

          fontSize: 36,

          fontFamily:
            'Arial',

          fill:
            '#111827',

          editable:
            true,
        },
      )

    applyObjectControls(
      text,
    )

    canvas.add(
      text,
    )

    canvas.setActiveObject(
      text,
    )

    canvas.requestRenderAll()

    text.enterEditing()

    text.selectAll()
  }

  function addRectangle() {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    const rectangle =
      new Rect({
        left: 250,
        top: 250,

        width: 200,
        height: 120,

        fill:
          '#dbeafe',

        stroke:
          '#2563eb',

        strokeWidth: 2,

        rx: 4,
        ry: 4,
      })

    applyObjectControls(
      rectangle,
    )

    canvas.add(
      rectangle,
    )

    canvas.setActiveObject(
      rectangle,
    )

    canvas.requestRenderAll()
  }

  function openImagePicker() {
    fileInputRef.current?.click()
  }

  async function addImage(
    file: File,
  ) {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    try {
      console.log(
        'Uploading document asset:',
        {
          name:
            file.name,

          type:
            file.type,

          size:
            file.size,
        },
      )

      /*
       * Upload permanently to Laravel.
       */
      const asset =
        await AssetService.create(
          file,
          'image',
        )

      console.log(
        'Document asset created:',
        asset,
      )

      const imageUrl =
        resolveAssetUrl(
          asset.url,
        )

      console.log(
        'Loading Fabric image:',
        imageUrl,
      )

      /*
       * Do NOT force:
       *
       * crossOrigin: 'anonymous'
       *
       * Public Laravel storage is being
       * loaded directly for display.
       */
      const image =
        await FabricImage.fromURL(
          imageUrl,
        )

      const maxWidth =
        400

      const maxHeight =
        300

      const originalWidth =
        image.width || 1

      const originalHeight =
        image.height || 1

      const scale =
        Math.min(
          maxWidth /
            originalWidth,

          maxHeight /
            originalHeight,

          1,
        )

      image.set({
        left: 300,
        top: 200,

        scaleX:
          scale,

        scaleY:
          scale,
      })

      const awakenImage =
        image as
          AwakenFabricObject

      awakenImage.awakenType =
        'asset-image'

      awakenImage.awakenAssetUuid =
        asset.uuid

      awakenImage.awakenAssetPath =
        asset.path

      awakenImage.awakenProtected =
        false

      applyObjectControls(
        image,
      )

      canvas.add(
        image,
      )

      canvas.setActiveObject(
        image,
      )

      image.setCoords()

      canvas.requestRenderAll()
    } catch (error) {
      console.error(
        'Unable to upload/load image:',
        error,
      )

      if (
        typeof error ===
          'object' &&
        error !== null &&
        'response' in error
      ) {
        const apiError =
          error as {
            response?: {
              status?: number
              data?: unknown
            }
          }

        console.error(
          'Asset API status:',
          apiError.response
            ?.status,
        )

        console.error(
          'Asset API response:',
          apiError.response
            ?.data,
        )
      }
    }
  }

  function handleImageChange(
    event:
      ChangeEvent<HTMLInputElement>,
  ) {
    const file =
      event.target.files?.[0]

    if (!file) {
      return
    }

    void addImage(
      file,
    )

    /*
     * Allows selecting the same
     * file again later.
     */
    event.target.value =
      ''
  }

  function handleTemplateLoaded() {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    canvas.backgroundColor =
      canvas.backgroundColor ??
      '#ffffff'

    canvas
      .getObjects()
      .forEach(
        object => {
          applyObjectControls(
            object,
          )

          const awakenObject =
            object as
              AwakenFabricObject

          if (
            awakenObject
              .awakenProtected
          ) {
            object.set({
              borderColor:
                '#7c3aed',

              cornerColor:
                '#7c3aed',

              cornerStrokeColor:
                '#ffffff',
            })
          }

          object.setCoords()
        },
      )

    canvas.discardActiveObject()

    canvas.requestRenderAll()
  }

  const activeCanvas =
    canvasReady
      ? canvasRef.current
      : null

  return (
    <Box
      sx={{
        width:
          '100%',

        height:
          '100%',

        display:
          'flex',

        flexDirection:
          'column',

        minHeight:
          0,

        bgcolor:
          '#eef2f7',
      }}
    >
      <Stack
        direction="row"
        spacing={1}
        alignItems="center"
        sx={{
          px: 2,
          py: 1,

          bgcolor:
            '#ffffff',

          borderBottom:
            '1px solid #e2e8f0',

          flexWrap:
            'wrap',

          rowGap:
            1,
        }}
      >
        <Typography
          fontWeight={700}
          sx={{
            mr: 1,
          }}
        >
          Trust AWAKEN Studio
        </Typography>

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <AddIcon />
          }
          disabled={
            !canvasReady
          }
          onClick={
            addText
          }
        >
          Text
        </Button>

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <CropSquareIcon />
          }
          disabled={
            !canvasReady
          }
          onClick={
            addRectangle
          }
        >
          Rectangle
        </Button>

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <ImageIcon />
          }
          disabled={
            !canvasReady
          }
          onClick={
            openImagePicker
          }
        >
          Image
        </Button>

        <input
          ref={
            fileInputRef
          }
          type="file"
          accept="image/png,image/jpeg,image/webp,image/svg+xml"
          hidden
          onChange={
            handleImageChange
          }
        />

        <FabricHistoryManager
          canvas={
            activeCanvas
          }
        />

        <FabricTextToolbar
          canvas={
            activeCanvas
          }
        />

        <FabricObjectToolbar
          canvas={
            activeCanvas
          }
        />

        <Box
          sx={{
            ml:
              'auto',
          }}
        >
          <FabricTemplateManager
            canvas={
              activeCanvas
            }
            onNew={
              newTemplate
            }
            onLoaded={
              handleTemplateLoaded
            }
          />
        </Box>
      </Stack>

      <Box
        sx={{
          flex: 1,

          minHeight:
            0,

          display:
            'flex',

          overflow:
            'hidden',
        }}
      >
        <Box
          sx={{
            flex: 1,

            minWidth:
              0,

            overflow:
              'auto',

            display:
              'flex',

            justifyContent:
              'center',

            alignItems:
              'flex-start',

            p: 4,
          }}
        >
          <Box
            sx={{
              boxShadow:
                4,

              lineHeight:
                0,

              bgcolor:
                '#ffffff',
            }}
          >
            <canvas
              ref={
                canvasElementRef
              }
            />
          </Box>
        </Box>

        <Box
          sx={{
            width:
              300,

            minWidth:
              300,

            height:
              '100%',

            bgcolor:
              '#ffffff',

            borderLeft:
              '1px solid #e2e8f0',

            overflow:
              'auto',
          }}
        >
          <Box
            sx={{
              p: 2,
            }}
          >
            <FabricVariablesPanel
              canvas={
                activeCanvas
              }
            />

            <Divider
              sx={{
                my: 2,
              }}
            />

            <FabricQrPanel
              canvas={
                activeCanvas
              }
            />

            <Divider
              sx={{
                my: 2,
              }}
            />

            <FabricBrandPanel
              canvas={
                activeCanvas
              }
            />
          </Box>

          <Divider />

          <FabricLayersPanel
            canvas={
              activeCanvas
            }
          />
        </Box>
      </Box>
    </Box>
  )
}

function applyObjectControls(
  object: FabricObject,
) {
  object.set({
    cornerColor:
      '#2563eb',

    cornerStrokeColor:
      '#ffffff',

    borderColor:
      '#2563eb',

    cornerSize:
      12,

    transparentCorners:
      false,

    borderScaleFactor:
      1.5,

    padding:
      2,
  })
}

function resolveAssetUrl(
  url: string,
): string {
  /*
   * Keep browser-local resources
   * untouched.
   */
  if (
    url.startsWith(
      'data:',
    ) ||
    url.startsWith(
      'blob:',
    )
  ) {
    return url
  }

  const apiUrl =
    import.meta.env
      .VITE_API_URL as
      string | undefined

  if (!apiUrl) {
    return url
  }

  try {
    /*
     * Example:
     *
     * http://127.0.0.1:8000/api/v1
     *
     * becomes:
     *
     * http://127.0.0.1:8000
     */
    const apiOrigin =
      new URL(
        apiUrl,
      ).origin

    /*
     * Backend may accidentally return:
     *
     * http://localhost/storage/...
     *
     * For Laravel public storage assets
     * we always use the API server origin.
     */
    if (
      url.startsWith(
        'http://',
      ) ||
      url.startsWith(
        'https://',
      )
    ) {
      const parsedUrl =
        new URL(
          url,
        )

      if (
        parsedUrl.pathname
          .startsWith(
            '/storage/',
          )
      ) {
        return new URL(
          parsedUrl.pathname +
            parsedUrl.search +
            parsedUrl.hash,
          apiOrigin,
        ).toString()
      }

      return url
    }

    /*
     * Relative storage URL:
     *
     * /storage/document-assets/...
     *
     * or:
     *
     * storage/document-assets/...
     */
    const path =
      url.startsWith('/')
        ? url
        : `/${url}`

    return new URL(
      path,
      apiOrigin,
    ).toString()
  } catch (error) {
    console.error(
      'Unable to resolve asset URL:',
      {
        url,
        apiUrl,
        error,
      },
    )

    return url
  }
}