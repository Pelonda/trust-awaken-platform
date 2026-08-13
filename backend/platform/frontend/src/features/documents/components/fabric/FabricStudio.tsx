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

import FabricDocumentSetup, {
  type DocumentSetupState,
  type DocumentSide,
} from './FabricDocumentSetup'

import {
  createBlankFabricPage,
  createFabricPageState,
  loadFabricPage,
  resizeFabricPage,
  serializeFabricPage,
  type FabricPageState,
} from './FabricPageManager'

import type {
  SavedFabricTemplate,
} from '../../services/FabricTemplateService'

interface AwakenFabricObject
  extends FabricObject {
  awakenProtected?: boolean
  awakenType?: string
  awakenVariable?: string
  awakenBrandAsset?: string
  awakenAssetUuid?: string
  awakenAssetPath?: string

  awakenQrCodePath?: string
  awakenVerificationUrl?: string
  awakenVerificationCode?: string
}

export default function FabricStudio() {
  /*
  |--------------------------------------------------------------------------
  | Canvas References
  |--------------------------------------------------------------------------
  */

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

  /*
  |--------------------------------------------------------------------------
  | Canvas Ready
  |--------------------------------------------------------------------------
  */

  const [
    canvasReady,
    setCanvasReady,
  ] =
    useState(false)

  /*
  |--------------------------------------------------------------------------
  | Document V2 State
  |--------------------------------------------------------------------------
  */

  const [
    activeSide,
    setActiveSide,
  ] =
    useState<DocumentSide>(
      'front',
    )

  const [
    documentSetup,
    setDocumentSetup,
  ] =
    useState<
      DocumentSetupState | null
    >(
      null,
    )

  /*
   * The page state lives outside React
   * rendering because Fabric itself owns
   * the active visual canvas.
   *
   * React only needs to know which side
   * is currently active.
   */

  const pageStateRef =
    useRef<FabricPageState>(
      createFabricPageState(
        1000,
        650,
      ),
    )

  /*
  |--------------------------------------------------------------------------
  | Initialize Fabric
  |--------------------------------------------------------------------------
  */

  useEffect(
    () => {
      if (
        !canvasElementRef.current
      ) {
        return
      }

      const canvas =
        new Canvas(
          canvasElementRef.current,
          {
            width:
              1000,

            height:
              650,

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

      /*
       * Initialize the Front page from
       * the actual Fabric canvas.
       */

      pageStateRef.current =
        createFabricPageState(
          canvas.getWidth(),
          canvas.getHeight(),
        )

      pageStateRef.current
        .pages.front =
        serializeFabricPage(
          canvas,
          'front',
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
    },
    [],
  )

  /*
  |--------------------------------------------------------------------------
  | Canvas Configuration
  |--------------------------------------------------------------------------
  */

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

  /*
  |--------------------------------------------------------------------------
  | Blank Canvas
  |--------------------------------------------------------------------------
  */

  function createBlankCanvas(
    canvas: Canvas,
  ) {
    canvas.discardActiveObject()

    canvas.clear()

    canvas.backgroundColor =
      '#ffffff'

    canvas.requestRenderAll()
  }

  /*
  |--------------------------------------------------------------------------
  | New Template
  |--------------------------------------------------------------------------
  */

  function newTemplate() {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    createBlankCanvas(
      canvas,
    )

    pageStateRef.current =
      createFabricPageState(
        canvas.getWidth(),
        canvas.getHeight(),
      )

    pageStateRef.current
      .pages.front =
      serializeFabricPage(
        canvas,
        'front',
      )

    pageStateRef.current
      .activePage =
      'front'

    setActiveSide(
      'front',
    )

    setDocumentSetup(
      null,
    )

    canvas.requestRenderAll()
  }

  /*
  |--------------------------------------------------------------------------
  | Side Switching
  |--------------------------------------------------------------------------
  |
  | Only one Fabric Canvas exists in the DOM.
  |
  | Before switching:
  |
  | 1. Serialize the current side.
  | 2. Store it in pageStateRef.
  | 3. Load the requested side.
  |
  */

  async function switchSide(
    nextSide:
      DocumentSide,
  ) {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    if (
      nextSide ===
      activeSide
    ) {
      return
    }

    /*
     * Save current side.
     */

    pageStateRef.current.pages[
      activeSide
    ] =
      serializeFabricPage(
        canvas,
        activeSide,
      )

    /*
     * Find requested side.
     */

    const nextPage =
      pageStateRef.current.pages[
        nextSide
      ]

    /*
     * Restore requested side.
     */

    await loadFabricPage(
      canvas,
      nextPage,
    )

    /*
     * Restore AWAKEN visual controls
     * after Fabric deserialization.
     */

    applyControlsToCanvas(
      canvas,
    )

    pageStateRef.current
      .activePage =
      nextSide

    setActiveSide(
      nextSide,
    )

    setDocumentSetup(
  current =>
    current
      ? {
          ...current,

          activeSide:
            nextSide,
        }
      : current,
)

    canvas.requestRenderAll()
  }

  /*
  |--------------------------------------------------------------------------
  | Document Setup
  |--------------------------------------------------------------------------
  */

  function handleDocumentSetup(
    setup:
      DocumentSetupState,
  ) {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    setDocumentSetup(
      setup,
    )

    /*
     * Both card sides always share the
     * same physical dimensions.
     */

    pageStateRef.current
      .pages.front =
      resizeFabricPage(
        pageStateRef.current
          .pages.front,

        setup.canvasWidth,
        setup.canvasHeight,
      )

    pageStateRef.current
      .pages.back =
      resizeFabricPage(
        pageStateRef.current
          .pages.back,

        setup.canvasWidth,
        setup.canvasHeight,
      )

    /*
     * FabricDocumentSetup has already
     * resized the active Canvas before
     * calling onChange().
     *
     * Serialize it now so page state
     * receives the new dimensions.
     */

    pageStateRef.current.pages[
      activeSide
    ] =
      serializeFabricPage(
        canvas,
        activeSide,
      )

    pageStateRef.current
      .activePage =
      activeSide
  }

  /*
  |--------------------------------------------------------------------------
  | Text
  |--------------------------------------------------------------------------
  */

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
          left:
            200,

          top:
            200,

          fontSize:
            36,

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

  /*
  |--------------------------------------------------------------------------
  | Rectangle
  |--------------------------------------------------------------------------
  */

  function addRectangle() {
    const canvas =
      canvasRef.current

    if (!canvas) {
      return
    }

    const rectangle =
      new Rect({
        left:
          250,

        top:
          250,

        width:
          200,

        height:
          120,

        fill:
          '#dbeafe',

        stroke:
          '#2563eb',

        strokeWidth:
          2,

        rx:
          4,

        ry:
          4,
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

  /*
  |--------------------------------------------------------------------------
  | Image Picker
  |--------------------------------------------------------------------------
  */

  function openImagePicker() {
    fileInputRef.current?.click()
  }

  /*
  |--------------------------------------------------------------------------
  | Image Upload
  |--------------------------------------------------------------------------
  */

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
       * Do not force crossOrigin here.
       *
       * Laravel public storage is loaded
       * directly for display.
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
        image.width ||
        1

      const originalHeight =
        image.height ||
        1

      const scale =
        Math.min(
          maxWidth /
            originalWidth,

          maxHeight /
            originalHeight,

          1,
        )

      image.set({
        left:
          300,

        top:
          200,

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
    } catch (
      error
    ) {
      console.error(
        'Unable to upload/load image:',
        error,
      )

      if (
        typeof error ===
          'object' &&
        error !==
          null &&
        'response' in
          error
      ) {
        const apiError =
          error as {
            response?: {
              status?:
                number

              data?:
                unknown
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

  /*
  |--------------------------------------------------------------------------
  | Image Input
  |--------------------------------------------------------------------------
  */

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
     * Allow selecting the same file
     * again later.
     */

    event.target.value =
      ''
  }

  /*
  |--------------------------------------------------------------------------
  | Template Loaded
  |--------------------------------------------------------------------------
  */

 function handleTemplateLoaded(
  template:
    SavedFabricTemplate,
) {
  const canvas =
    canvasRef.current

  if (!canvas) {
    return
  }

  const savedPages =
    Array.isArray(
      template.canvas.pages,
    )
      ? template.canvas.pages
      : []

  const frontPage =
    savedPages.find(
      page =>
        page.key ===
        'front',
    )

  const backPage =
    savedPages.find(
      page =>
        page.key ===
        'back',
    )

  /*
   * V2 template.
   */

  if (frontPage) {
    pageStateRef.current.pages.front = {
      ...frontPage,
      key:
        'front',
    }

    pageStateRef.current.pages.back =
      backPage
        ? {
            ...backPage,
            key:
              'back',
          }
        : createBlankFabricPage(
            'back',
            frontPage.canvas_width,
            frontPage.canvas_height,
          )

    pageStateRef.current.activePage =
      'front'

    setActiveSide(
      'front',
    )

    /*
     * Restore V2 setup metadata.
     */

    const paper =
      template.canvas.paper

    if (paper) {
      setDocumentSetup({
        schemaVersion:
          2,

        documentType:
          template.document_type ??
          template.canvas.document_type ??
          'certificate',

        language:
          template.language ??
          template.canvas.language ??
          'en',

        paperSize:
          template.paper_size ??
          paper.preset ??
          'custom',

        orientation:
          template.orientation ??
          paper.orientation ??
          'landscape',

        paperWidth:
          Number(
            template.paper_width ??
            paper.width,
          ),

        paperHeight:
          Number(
            template.paper_height ??
            paper.height,
          ),

        paperUnit:
          template.paper_unit ??
          paper.unit ??
          'px',

        canvasWidth:
          frontPage.canvas_width,

        canvasHeight:
          frontPage.canvas_height,

        activeSide:
          'front',

        hasBackSide:
          Boolean(
            backPage,
          ),
      })
    }
  } else {
    /*
     * Legacy V1 template.
     *
     * FabricTemplateManager already loaded
     * its single canvas. Capture that as
     * the Front page.
     */

    pageStateRef.current =
      createFabricPageState(
        canvas.getWidth(),
        canvas.getHeight(),
      )

    pageStateRef.current.pages.front =
      serializeFabricPage(
        canvas,
        'front',
      )

    pageStateRef.current.activePage =
      'front'

    setActiveSide(
      'front',
    )

    setDocumentSetup(
      null,
    )
  }

  canvas.backgroundColor =
    canvas.backgroundColor ??
    '#ffffff'

  applyControlsToCanvas(
    canvas,
  )

  canvas.discardActiveObject()

  canvas.requestRenderAll()
}

  /*
  |--------------------------------------------------------------------------
  | Active Canvas
  |--------------------------------------------------------------------------
  */

  const activeCanvas =
    canvasReady
      ? canvasRef.current
      : null

  /*
  |--------------------------------------------------------------------------
  | Render
  |--------------------------------------------------------------------------
  */

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
      {/*
      |--------------------------------------------------------------------------
      | Toolbar
      |--------------------------------------------------------------------------
      */}

      <Stack
        direction="row"
        spacing={1}
        alignItems="center"
        sx={{
          px:
            2,

          py:
            1,

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
            mr:
              1,
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

            documentSetup={
              documentSetup
            }

            activeSide={
              activeSide
            }

            getPages={() => {
              const canvas =
                canvasRef.current

              /*
               * Always serialize the
               * currently visible side
               * immediately before Save.
               */

              if (canvas) {
                pageStateRef.current
                  .pages[
                    activeSide
                  ] =
                  serializeFabricPage(
                    canvas,
                    activeSide,
                  )
              }

              return {
                ...pageStateRef
                  .current.pages,
              }
            }}

            onNew={
              newTemplate
            }

            onLoaded={
              handleTemplateLoaded
            }
          />
        </Box>
      </Stack>

      {/*
      |--------------------------------------------------------------------------
      | Workspace
      |--------------------------------------------------------------------------
      */}

      <Box
        sx={{
          flex:
            1,

          minHeight:
            0,

          display:
            'flex',

          overflow:
            'hidden',
        }}
      >
        {/*
        |--------------------------------------------------------------------------
        | Canvas
        |--------------------------------------------------------------------------
        */}

        <Box
          sx={{
            flex:
              1,

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

            p:
              4,
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

        {/*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */}

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
              p:
                2,
            }}
          >
        <FabricDocumentSetup
  canvas={
    activeCanvas
  }

  value={
    documentSetup
  }

  activeSide={
    activeSide
  }

  onSideChange={
    switchSide
  }

  onChange={
    handleDocumentSetup
  }
/>

            <Divider
              sx={{
                my:
                  2,
              }}
            />

            <FabricVariablesPanel
              canvas={
                activeCanvas
              }
            />

            <Divider
              sx={{
                my:
                  2,
              }}
            />

            <FabricQrPanel
              canvas={
                activeCanvas
              }
            />

            <Divider
              sx={{
                my:
                  2,
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

/*
|--------------------------------------------------------------------------
| Apply AWAKEN Controls
|--------------------------------------------------------------------------
*/

function applyControlsToCanvas(
  canvas: Canvas,
) {
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
}

/*
|--------------------------------------------------------------------------
| Object Controls
|--------------------------------------------------------------------------
*/

function applyObjectControls(
  object:
    FabricObject,
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

/*
|--------------------------------------------------------------------------
| Resolve Asset URL
|--------------------------------------------------------------------------
*/

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
     * Backend may return:
     *
     * http://localhost/storage/...
     *
     * Laravel public storage assets
     * must use the API server origin.
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
      url.startsWith(
        '/',
      )
        ? url
        : `/${url}`

    return new URL(
      path,
      apiOrigin,
    ).toString()
  } catch (
    error
  ) {
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