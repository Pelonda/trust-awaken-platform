import {
  Alert,
  Button,
  CircularProgress,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  FormControl,
  IconButton,
  InputLabel,
  List,
  ListItemButton,
  ListItemText,
  MenuItem,
  Select,
  Snackbar,
  Stack,
  Tab,
  Tabs,
  TextField,
  Tooltip,
} from '@mui/material'

import AddIcon from '@mui/icons-material/Add'
import SaveIcon from '@mui/icons-material/Save'
import FolderOpenIcon from '@mui/icons-material/FolderOpen'
import ContentCopyIcon from '@mui/icons-material/ContentCopy'
import DeleteIcon from '@mui/icons-material/Delete'
import StarIcon from '@mui/icons-material/Star'
import StarBorderIcon from '@mui/icons-material/StarBorder'

import {
  useEffect,
  useState,
} from 'react'

import type {
  Canvas,
  FabricObject,
} from 'fabric'

import FabricTemplateService, {
  type FabricCanvasJSON,
  type FabricPageJSON,
  type SaveFabricTemplateOptions,
  type SavedFabricTemplate,
} from '../../services/FabricTemplateService'

import type {
  DocumentSetupState,
  DocumentSide,
} from './FabricDocumentSetup'

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

interface Props {
  canvas:
    Canvas | null

  documentSetup:
    DocumentSetupState | null

  activeSide:
    DocumentSide

  getPages():
    Record<
      DocumentSide,
      FabricPageJSON
    >

  onNew(): void

  onLoaded(
    template:
      SavedFabricTemplate,
  ): void
}

/*
|--------------------------------------------------------------------------
| Template Manager View
|--------------------------------------------------------------------------
*/

type TemplateTab =
  | 'mine'
  | 'library'

/*
|--------------------------------------------------------------------------
| Trust AWAKEN Fabric Metadata
|--------------------------------------------------------------------------
*/

const CUSTOM_PROPERTIES = [
  'awakenType',
  'awakenVariable',
  'awakenProtected',
  'awakenBrandAsset',
  'awakenAssetUuid',
  'awakenAssetPath',
  'awakenQrCodePath',
  'awakenVerificationUrl',
  'awakenVerificationCode',
] as const

interface AwakenSerializableObject {
  awakenType?:
    string

  awakenVariable?:
    string

  awakenProtected?:
    boolean

  awakenBrandAsset?:
    string

  awakenAssetUuid?:
    string

  awakenAssetPath?:
    string

  awakenQrCodePath?:
    string

  awakenVerificationUrl?:
    string

  awakenVerificationCode?:
    string
}

/*
|--------------------------------------------------------------------------
| Serialize Canvas
|--------------------------------------------------------------------------
|
| The top-level Fabric document keeps active-page compatibility while
| Template Schema V2 stores all independent pages in canvas.pages[].
|
*/

function serializeCanvas(
  canvas:
    Canvas,
): FabricCanvasJSON {
  const json =
    canvas.toJSON(
      [
        ...CUSTOM_PROPERTIES,
      ],
    ) as FabricCanvasJSON

  json.canvas_width =
    canvas.getWidth()

  json.canvas_height =
    canvas.getHeight()

  /*
   * Explicitly preserve AWAKEN metadata.
   */

  const liveObjects =
    canvas.getObjects()

  const serializedObjects =
    Array.isArray(
      json.objects,
    )
      ? json.objects
      : []

  serializedObjects.forEach(
    (
      serializedObject,
      index,
    ) => {
      if (
        !serializedObject ||
        typeof serializedObject !==
          'object'
      ) {
        return
      }

      const liveObject =
        liveObjects[
          index
        ] as
          | (
              FabricObject &
              AwakenSerializableObject
            )
          | undefined

      if (!liveObject) {
        return
      }

      const target =
        serializedObject as
          Record<
            string,
            unknown
          >

      if (
        liveObject
          .awakenType !==
        undefined
      ) {
        target.awakenType =
          liveObject.awakenType
      }

      if (
        liveObject
          .awakenVariable !==
        undefined
      ) {
        target.awakenVariable =
          liveObject.awakenVariable
      }

      if (
        liveObject
          .awakenProtected !==
        undefined
      ) {
        target.awakenProtected =
          liveObject.awakenProtected
      }

      if (
        liveObject
          .awakenBrandAsset !==
        undefined
      ) {
        target.awakenBrandAsset =
          liveObject.awakenBrandAsset
      }

      if (
        liveObject
          .awakenAssetUuid !==
        undefined
      ) {
        target.awakenAssetUuid =
          liveObject.awakenAssetUuid
      }

      if (
        liveObject
          .awakenAssetPath !==
        undefined
      ) {
        target.awakenAssetPath =
          liveObject.awakenAssetPath
      }

      if (
        liveObject
          .awakenQrCodePath !==
        undefined
      ) {
        target.awakenQrCodePath =
          liveObject.awakenQrCodePath
      }

      if (
        liveObject
          .awakenVerificationUrl !==
        undefined
      ) {
        target.awakenVerificationUrl =
          liveObject
            .awakenVerificationUrl
      }

      if (
        liveObject
          .awakenVerificationCode !==
        undefined
      ) {
        target.awakenVerificationCode =
          liveObject
            .awakenVerificationCode
      }
    },
  )

  return json
}

/*
|--------------------------------------------------------------------------
| Legacy Page
|--------------------------------------------------------------------------
|
| V1 templates have no pages[] collection.
|
*/

function legacyPage(
  canvas:
    FabricCanvasJSON,
): FabricPageJSON {
  return {
    key:
      'front',

    canvas_width:
      canvas.canvas_width ??
      1000,

    canvas_height:
      canvas.canvas_height ??
      650,

    version:
      canvas.version,

    background:
      canvas.background ??
      '#ffffff',

    objects:
      Array.isArray(
        canvas.objects,
      )
        ? canvas.objects
        : [],
  }
}

/*
|--------------------------------------------------------------------------
| Component
|--------------------------------------------------------------------------
*/

export default function FabricTemplateManager({
  canvas,
  documentSetup,
  activeSide,
  getPages,
  onNew,
  onLoaded,
}: Props) {
  const [
    templateName,
    setTemplateName,
  ] =
    useState(
      'Untitled Template',
    )

  const [
    currentTemplateId,
    setCurrentTemplateId,
  ] =
    useState<
      number | null
    >(
      null,
    )

  /*
  |--------------------------------------------------------------------------
  | Tenant Templates
  |--------------------------------------------------------------------------
  */

  const [
    templates,
    setTemplates,
  ] =
    useState<
      SavedFabricTemplate[]
    >(
      [],
    )

  /*
  |--------------------------------------------------------------------------
  | Professional Library
  |--------------------------------------------------------------------------
  */

  const [
    libraryTemplates,
    setLibraryTemplates,
  ] =
    useState<
      SavedFabricTemplate[]
    >(
      [],
    )

  const [
    templateTab,
    setTemplateTab,
  ] =
    useState<TemplateTab>(
      'mine',
    )

  const [
    typeFilter,
    setTypeFilter,
  ] =
    useState(
      'all',
    )

  const [
    languageFilter,
    setLanguageFilter,
  ] =
    useState(
      'all',
    )

    const [
  orientationFilter,
  setOrientationFilter,
] =
  useState(
    'all',
  )

  /*
  |--------------------------------------------------------------------------
  | Dialog / Loading
  |--------------------------------------------------------------------------
  */

  const [
    dialogOpen,
    setDialogOpen,
  ] =
    useState(
      false,
    )

  const [
    loading,
    setLoading,
  ] =
    useState(
      false,
    )

  const [
    saving,
    setSaving,
  ] =
    useState(
      false,
    )

  const [
    message,
    setMessage,
  ] =
    useState<
      string | null
    >(
      null,
    )

  const [
    error,
    setError,
  ] =
    useState<
      string | null
    >(
      null,
    )

  /*
  |--------------------------------------------------------------------------
  | Refresh
  |--------------------------------------------------------------------------
  */

  async function refresh() {
    try {
      setLoading(
        true,
      )

      setError(
        null,
      )

      const [
        tenantResult,
        libraryResult,
      ] =
        await Promise.all([
          FabricTemplateService
            .all(),

          FabricTemplateService
            .library(),
        ])

      setTemplates(
        tenantResult,
      )

      setLibraryTemplates(
        libraryResult,
      )
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to retrieve templates.',
      )
    } finally {
      setLoading(
        false,
      )
    }
  }

  useEffect(
    () => {
      void refresh()
    },
    [],
  )

  /*
  |--------------------------------------------------------------------------
  | Save
  |--------------------------------------------------------------------------
  */

  async function save() {
    if (!canvas) {
      return
    }

    const name =
      templateName.trim()

    if (!name) {
      setError(
        'Enter a template name.',
      )

      return
    }

    try {
      setSaving(
        true,
      )

      setError(
        null,
      )

      /*
       * Keep a top-level representation
       * of the currently visible page.
       */

      const activeCanvasJson =
        serializeCanvas(
          canvas,
        )

      /*
       * If Document Setup has not yet
       * been applied, preserve current
       * behavior as a V2 custom document.
       */

      const setup:
        DocumentSetupState =
        documentSetup ?? {
          schemaVersion:
            2,

          documentType:
            'certificate',

          language:
            'en',

          paperSize:
            'custom',

          orientation:
            'landscape',

          paperWidth:
            canvas.getWidth(),

          paperHeight:
            canvas.getHeight(),

          paperUnit:
            'px',

          canvasWidth:
            canvas.getWidth(),

          canvasHeight:
            canvas.getHeight(),

          activeSide,

          hasBackSide:
            false,
        }

      /*
       * FabricStudio serializes the
       * currently visible side immediately
       * before returning these pages.
       */

      const pages =
        getPages()

      const pageList:
        FabricPageJSON[] =
        setup.hasBackSide
          ? [
              pages.front,
              pages.back,
            ]
          : [
              pages.front,
            ]

      /*
       * Template Schema V2 document.
       */

      const document:
        FabricCanvasJSON = {
          ...activeCanvasJson,

          schema_version:
            2,

          engine:
            'fabric',

          engine_version:
            7,

          document_type:
            setup.documentType,

          language:
            setup.language,

          paper: {
            preset:
              setup.paperSize,

            width:
              setup.paperWidth,

            height:
              setup.paperHeight,

            unit:
              setup.paperUnit,

            orientation:
              setup.orientation,
          },

          pages:
            pageList,
        }

      const options:
        SaveFabricTemplateOptions = {
          documentType:
            setup.documentType,

          language:
            setup.language,

          paperSize:
            setup.paperSize,

          orientation:
            setup.orientation,

          paperWidth:
            setup.paperWidth,

          paperHeight:
            setup.paperHeight,

          paperUnit:
            setup.paperUnit,

          schemaVersion:
            2,

          settings: {
            active_side:
              activeSide,

            has_back_side:
              setup.hasBackSide,

            page_count:
              pageList.length,
          },
        }

      const saved =
        await FabricTemplateService
          .save(
            name,
            document,
            options,
            currentTemplateId,
          )

      setCurrentTemplateId(
        saved.id,
      )

      setTemplateName(
        saved.name,
      )

      setMessage(
        currentTemplateId
          ? `Template "${saved.name}" updated.`
          : `Template "${saved.name}" created.`,
      )

      await refresh()
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to save template.',
      )
    } finally {
      setSaving(
        false,
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Load Tenant Template
  |--------------------------------------------------------------------------
  */

  async function load(
    template:
      SavedFabricTemplate,
  ) {
    if (!canvas) {
      return
    }

    try {
      setLoading(
        true,
      )

      setError(
        null,
      )

      const loaded =
        await FabricTemplateService
          .get(
            template.id,
          )

      await loadResolvedTemplate(
        loaded,
      )
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to load template.',
      )
    } finally {
      setLoading(
        false,
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Load Resolved Tenant Template
  |--------------------------------------------------------------------------
  |
  | Used by normal template loading and
  | immediately after Use Template.
  |
  */

  async function loadResolvedTemplate(
    loaded:
      SavedFabricTemplate,
  ) {
    if (!canvas) {
      return
    }

    canvas.discardActiveObject()

    canvas.clear()

    const pages =
      Array.isArray(
        loaded.canvas.pages,
      )
        ? loaded.canvas.pages
        : []

    const frontPage =
      pages.find(
        page =>
          page.key ===
          'front',
      ) ??
      legacyPage(
        loaded.canvas,
      )

    canvas.setDimensions({
      width:
        frontPage.canvas_width,

      height:
        frontPage.canvas_height,
    })

    await canvas.loadFromJSON({
      version:
        frontPage.version,

      objects:
        frontPage.objects,

      background:
        frontPage.background ??
        '#ffffff',
    })

    canvas.backgroundColor =
      frontPage.background ??
      '#ffffff'

    canvas.calcOffset()

    canvas.requestRenderAll()

    setCurrentTemplateId(
      loaded.id,
    )

    setTemplateName(
      loaded.name,
    )

    setDialogOpen(
      false,
    )

    onLoaded(
      loaded,
    )

    setMessage(
      `Template "${loaded.name}" loaded.`,
    )
  }

  /*
  |--------------------------------------------------------------------------
  | New
  |--------------------------------------------------------------------------
  */

  function createNew() {
    setCurrentTemplateId(
      null,
    )

    setTemplateName(
      'Untitled Template',
    )

    setDialogOpen(
      false,
    )

    setTemplateTab(
      'mine',
    )

    onNew()

    setMessage(
      'New template started.',
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Duplicate
  |--------------------------------------------------------------------------
  */

  async function duplicate(
    template:
      SavedFabricTemplate,
  ) {
    try {
      setLoading(
        true,
      )

      setError(
        null,
      )

      const copy =
        await FabricTemplateService
          .duplicate(
            template,
          )

      await refresh()

      setMessage(
        `"${copy.name}" created.`,
      )
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to duplicate template.',
      )
    } finally {
      setLoading(
        false,
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Delete
  |--------------------------------------------------------------------------
  */

  async function remove(
    template:
      SavedFabricTemplate,
  ) {
    if (
      template.is_system
    ) {
      setError(
        'System templates cannot be deleted.',
      )

      return
    }

    const confirmed =
      window.confirm(
        `Delete "${template.name}"?`,
      )

    if (!confirmed) {
      return
    }

    try {
      setLoading(
        true,
      )

      setError(
        null,
      )

      await FabricTemplateService
        .remove(
          template.id,
        )

      if (
        currentTemplateId ===
        template.id
      ) {
        setCurrentTemplateId(
          null,
        )

        setTemplateName(
          'Untitled Template',
        )

        onNew()
      }

      await refresh()

      setMessage(
        `"${template.name}" deleted.`,
      )
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to delete template.',
      )
    } finally {
      setLoading(
        false,
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Default
  |--------------------------------------------------------------------------
  */

  async function makeDefault(
    template:
      SavedFabricTemplate,
  ) {
    try {
      setLoading(
        true,
      )

      setError(
        null,
      )

      await FabricTemplateService
        .setDefault(
          template.id,
        )

      await refresh()

      setMessage(
        `"${template.name}" set as default.`,
      )
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to set default template.',
      )
    } finally {
      setLoading(
        false,
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Use Professional Template
  |--------------------------------------------------------------------------
  */

  async function useProfessionalTemplate(
    template:
      SavedFabricTemplate,
  ) {
    if (!canvas) {
      setError(
        'Document Studio is not ready.',
      )

      return
    }

    try {
      setLoading(
        true,
      )

      setError(
        null,
      )

      /*
       * Laravel creates a tenant-owned,
       * editable copy.
       */

      const copy =
        await FabricTemplateService
          .useTemplate(
            template,
          )

      /*
       * Refresh My Templates and the
       * professional library.
       */

      const [
        tenantResult,
        libraryResult,
      ] =
        await Promise.all([
          FabricTemplateService
            .all(),

          FabricTemplateService
            .library(),
        ])

      setTemplates(
        tenantResult,
      )

      setLibraryTemplates(
        libraryResult,
      )

      /*
       * The tenant copy is now the active
       * editable template.
       */

      setTemplateTab(
        'mine',
      )

      await loadResolvedTemplate(
        copy,
      )

      setMessage(
        `"${copy.name}" added to your organization and loaded.`,
      )
    } catch (
      exception
    ) {
      console.error(
        exception,
      )

      setError(
        exception instanceof
          Error
          ? exception.message
          : 'Unable to use professional template.',
      )
    } finally {
      setLoading(
        false,
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Template Description
  |--------------------------------------------------------------------------
  */

  function templateDescription(
    template:
      SavedFabricTemplate,
  ): string {
    const pageCount =
      template.canvas.pages
        ?.length ??
      1

    return [
      displayDocumentType(
        template,
      ),

      displayLanguage(
        template,
      ),

      displayPaperSize(
        template.paper_size,
      ),

      displayOrientation(
        template.orientation,
      ),

      pageCount === 2
        ? 'Front + Back'
        : `${pageCount} ${
            pageCount === 1
              ? 'page'
              : 'pages'
          }`,
    ].join(
      ' • ',
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Display Document Type
  |--------------------------------------------------------------------------
  */

  function displayDocumentType(
    template:
      SavedFabricTemplate,
  ): string {
    const type =
      template.document_type ??
      template.type

    switch (type) {
      case 'certificate':
        return 'Certificate'

      case 'diploma':
        return 'Diploma'

      case 'badge':
        return 'Badge'

      case 'id_card':
        return 'ID Card'

      case 'training_card':
        return 'Training Card'

      case 'custom':
        return 'Custom'

      default:
        return type
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Display Language
  |--------------------------------------------------------------------------
  */

  function displayLanguage(
    template:
      SavedFabricTemplate,
  ): string {
    switch (
      template.language
    ) {
      case 'fr':
        return 'Français'

      case 'en-fr':
        return 'English / Français'

      case 'en':
      default:
        return 'English'
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Display Paper Size
  |--------------------------------------------------------------------------
  */

  function displayPaperSize(
    paperSize:
      string,
  ): string {
    switch (
      paperSize
    ) {
      case 'a0':
        return 'A0'

      case 'a1':
        return 'A1'

      case 'a2':
        return 'A2'

      case 'a3':
        return 'A3'

      case 'a4':
        return 'A4'

      case 'a5':
        return 'A5'

      case 'a6':
        return 'A6'

      case 'letter':
        return 'US Letter'

      case 'legal':
        return 'US Legal'

      case 'tabloid':
        return 'Tabloid / Ledger'

      case 'cr80':
        return 'CR80 / ID-1'

      case 'id2':
        return 'ID-2'

      case 'id3':
        return 'ID-3'

      case 'badge-square':
        return 'Square Badge'

      case 'badge-landscape':
        return 'Landscape Badge'

      case 'badge-portrait':
        return 'Portrait Badge'

      case 'custom':
        return 'Custom'

      default:
        return paperSize
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Display Orientation
  |--------------------------------------------------------------------------
  */

  function displayOrientation(
    orientation:
      string,
  ): string {
    return orientation ===
      'portrait'
      ? 'Portrait'
      : 'Landscape'
  }

  /*
  |--------------------------------------------------------------------------
  | Professional Library Filters
  |--------------------------------------------------------------------------
  */

  const filteredLibraryTemplates =
    libraryTemplates.filter(
      template => {
        const type =
          template.document_type ??
          template.type

        if (
          typeFilter !==
            'all' &&
          type !==
            typeFilter
        ) {
          return false
        }

        if (
          languageFilter !==
            'all' &&
          template.language !==
            languageFilter
        ) {
          return false
        }

        if (
  orientationFilter !==
    'all' &&
  template.orientation !==
    orientationFilter
) {
  return false
}

        return true
      },
    )

  /*
  |--------------------------------------------------------------------------
  | Render
  |--------------------------------------------------------------------------
  */

  return (
    <>
      <Stack
        direction="row"
        spacing={1}
        alignItems="center"
      >
        <TextField
          size="small"
          label="Template name"
          value={
            templateName
          }
          onChange={
            event =>
              setTemplateName(
                event.target.value,
              )
          }
          sx={{
            width:
              240,
          }}
        />

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <AddIcon />
          }
          onClick={
            createNew
          }
        >
          New
        </Button>

        <Button
          size="small"
          variant="contained"
          startIcon={
            saving
              ? (
                  <CircularProgress
                    size={16}
                    color="inherit"
                  />
                )
              : (
                  <SaveIcon />
                )
          }
          disabled={
            saving ||
            !canvas
          }
          onClick={() =>
            void save()
          }
        >
          {
            currentTemplateId
              ? 'Save Changes'
              : 'Save'
          }
        </Button>

        <Button
          size="small"
          variant="outlined"
          startIcon={
            <FolderOpenIcon />
          }
          onClick={() => {
            setDialogOpen(
              true,
            )

            void refresh()
          }}
        >
          Templates
        </Button>
      </Stack>

      <Dialog
        open={
          dialogOpen
        }
        onClose={() =>
          setDialogOpen(
            false,
          )
        }
        fullWidth
        maxWidth="md"
      >
        <DialogTitle>
          Document Templates
        </DialogTitle>

        <DialogContent
          dividers
        >
          {/*
          |--------------------------------------------------------------------------
          | Tabs
          |--------------------------------------------------------------------------
          */}

          <Tabs
            value={
              templateTab
            }
            onChange={(
              _event,
              value:
                TemplateTab,
            ) =>
              setTemplateTab(
                value,
              )
            }
            sx={{
              mb:
                2,
            }}
          >
            <Tab
              value="mine"
              label="My Templates"
            />

            <Tab
              value="library"
              label="Professional Library"
            />
          </Tabs>

          {/*
          |--------------------------------------------------------------------------
          | Professional Library Filters
          |--------------------------------------------------------------------------
          */}

          {templateTab ===
            'library' && (
            <Stack
              direction={{
                xs:
                  'column',

                sm:
                  'row',
              }}
              spacing={1}
              sx={{
                mb:
                  2,
              }}
            >
              <FormControl
                size="small"
                fullWidth
              >
                <InputLabel>
                  Document Type
                </InputLabel>

                <Select
                  label="Document Type"
                  value={
                    typeFilter
                  }
                  onChange={
                    event =>
                      setTypeFilter(
                        event.target
                          .value,
                      )
                  }
                >
                  <MenuItem
                    value="all"
                  >
                    All Types
                  </MenuItem>

                  <MenuItem
                    value="certificate"
                  >
                    Certificate
                  </MenuItem>

                  <MenuItem
                    value="diploma"
                  >
                    Diploma
                  </MenuItem>

                  <MenuItem
                    value="id_card"
                  >
                    ID Card
                  </MenuItem>

                  <MenuItem
                    value="badge"
                  >
                    Badge
                  </MenuItem>

                  <MenuItem
                    value="training_card"
                  >
                    Training Card
                  </MenuItem>
                </Select>
              </FormControl>

              <FormControl
                size="small"
                fullWidth
              >
                <InputLabel>
                  Language
                </InputLabel>

                <Select
                  label="Language"
                  value={
                    languageFilter
                  }
                  onChange={
                    event =>
                      setLanguageFilter(
                        event.target
                          .value,
                      )
                  }
                >
                  <MenuItem
                    value="all"
                  >
                    All Languages
                  </MenuItem>

                  <MenuItem
                    value="en"
                  >
                    English
                  </MenuItem>

                  <MenuItem
                    value="fr"
                  >
                    Français
                  </MenuItem>

                  <MenuItem
                    value="en-fr"
                  >
                    English / Français
                  </MenuItem>
                </Select>
              </FormControl>

              <FormControl
  size="small"
  fullWidth
>
  <InputLabel>
    Orientation
  </InputLabel>

  <Select
    label="Orientation"
    value={
      orientationFilter
    }
    onChange={
      event =>
        setOrientationFilter(
          event.target.value,
        )
    }
  >
    <MenuItem
      value="all"
    >
      All Orientations
    </MenuItem>

    <MenuItem
      value="landscape"
    >
      Landscape
    </MenuItem>

    <MenuItem
      value="portrait"
    >
      Portrait
    </MenuItem>
  </Select>
</FormControl>
            </Stack>
          )}

          {/*
          |--------------------------------------------------------------------------
          | Loading
          |--------------------------------------------------------------------------
          */}

          {loading ? (
            <Stack
              alignItems="center"
              sx={{
                py:
                  4,
              }}
            >
              <CircularProgress />
            </Stack>
          ) : templateTab ===
              'mine' ? (
            /*
            |--------------------------------------------------------------------------
            | My Templates
            |--------------------------------------------------------------------------
            */

            templates.length ===
              0 ? (
              <Alert
                severity="info"
              >
                You do not have any templates yet.
                Open the Professional Library to
                start with a preconfigured design.
              </Alert>
            ) : (
              <List
                disablePadding
              >
                {templates.map(
                  template => (
                    <ListItemButton
                      key={
                        template.id
                      }
                      selected={
                        currentTemplateId ===
                        template.id
                      }
                      onClick={() =>
                        void load(
                          template,
                        )
                      }
                    >
                      <ListItemText
                        primary={
                          template.name
                        }
                        secondary={
                          templateDescription(
                            template,
                          )
                        }
                      />

                      <Stack
                        direction="row"
                        onClick={
                          event =>
                            event
                              .stopPropagation()
                        }
                      >
                        <Tooltip
                          title={
                            template.default
                              ? 'Default'
                              : 'Set default'
                          }
                        >
                          <span>
                            <IconButton
                              size="small"
                              disabled={
                                template.default
                              }
                              onClick={() =>
                                void makeDefault(
                                  template,
                                )
                              }
                            >
                              {template.default ? (
                                <StarIcon
                                  color="warning"
                                  fontSize="small"
                                />
                              ) : (
                                <StarBorderIcon
                                  fontSize="small"
                                />
                              )}
                            </IconButton>
                          </span>
                        </Tooltip>

                        <Tooltip
                          title="Duplicate"
                        >
                          <IconButton
                            size="small"
                            onClick={() =>
                              void duplicate(
                                template,
                              )
                            }
                          >
                            <ContentCopyIcon
                              fontSize="small"
                            />
                          </IconButton>
                        </Tooltip>

                        <Tooltip
                          title="Delete"
                        >
                          <IconButton
                            size="small"
                            color="error"
                            onClick={() =>
                              void remove(
                                template,
                              )
                            }
                          >
                            <DeleteIcon
                              fontSize="small"
                            />
                          </IconButton>
                        </Tooltip>
                      </Stack>
                    </ListItemButton>
                  ),
                )}
              </List>
            )
          ) : (
            /*
            |--------------------------------------------------------------------------
            | Professional Library
            |--------------------------------------------------------------------------
            */

            filteredLibraryTemplates
              .length ===
              0 ? (
              <Alert
                severity="info"
              >
                No professional templates match
                the selected filters.
              </Alert>
            ) : (
              <List
                disablePadding
              >
                {filteredLibraryTemplates.map(
                  template => {
                    const pageCount =
                      template.canvas
                        .pages
                        ?.length ??
                      1

                    return (
                      <ListItemButton
                        key={
                          template.id
                        }
                        sx={{
                          py:
                            1.5,

                          alignItems:
                            'center',
                        }}
                      >
                        <ListItemText
                          primary={
                            template.name
                          }
                          secondary={
                            [
                              displayDocumentType(
                                template,
                              ),

                              displayLanguage(
                                template,
                              ),

                              displayPaperSize(
                                template
                                  .paper_size,
                              ),

                              pageCount ===
                                2
                                ? 'Front + Back'
                                : 'Single page',
                            ].join(
                              ' • ',
                            )
                          }
                        />

                        <Button
                          size="small"
                          variant="contained"
                          disabled={
                            loading ||
                            !canvas
                          }
                          onClick={
                            event => {
                              event
                                .stopPropagation()

                              void useProfessionalTemplate(
                                template,
                              )
                            }
                          }
                        >
                          Use Template
                        </Button>
                      </ListItemButton>
                    )
                  },
                )}
              </List>
            )
          )}
        </DialogContent>

        <DialogActions>
          {templateTab ===
            'mine' && (
            <Button
              startIcon={
                <AddIcon />
              }
              onClick={
                createNew
              }
            >
              New Template
            </Button>
          )}

          <Button
            onClick={() =>
              setDialogOpen(
                false,
              )
            }
          >
            Close
          </Button>
        </DialogActions>
      </Dialog>

      <Snackbar
        open={
          Boolean(
            message,
          )
        }
        autoHideDuration={
          3000
        }
        message={
          message
        }
        onClose={() =>
          setMessage(
            null,
          )
        }
      />

      <Snackbar
        open={
          Boolean(
            error,
          )
        }
        autoHideDuration={
          6000
        }
        onClose={() =>
          setError(
            null,
          )
        }
      >
        <Alert
          severity="error"
          onClose={() =>
            setError(
              null,
            )
          }
        >
          {error}
        </Alert>
      </Snackbar>
    </>
  )
}