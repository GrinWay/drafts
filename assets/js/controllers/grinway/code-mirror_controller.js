import { keymap, Decoration, DecorationSet, MatchDecorator, ViewPlugin, ViewUpdate, WidgetType } from "@codemirror/view"
import { Controller } from '@hotwired/stimulus'
import { basicSetup, EditorView } from "codemirror"
import { EditorState, Compartment, EditorSelection, Text, StateField, StateEffect } from "@codemirror/state"
import { defaultKeymap, indentWithTab } from "@codemirror/commands"
import { php } from "@codemirror/lang-php"
import { python } from "@codemirror/lang-python"
import { javascript } from "@codemirror/lang-javascript"
import { syntaxHighlighting, HighlightStyle } from "@codemirror/language"
import { tags } from "@lezer/highlight"

export default class extends Controller {
	
	static targets = [
		'editor',
	]
	
	connect() {
		if (!this.editor) {
			return
		}
		
		this.language = new Compartment
		this.tabSize = new Compartment
		
		const phpDocMainComment = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo, sed, facere iusto ipsum quas possimus animi eos aliquam architecto debitis sint molestias dignissimos nostrum necessitatibus nesciunt vel ullam perferendis neque similique ab quia quam optio laborum blanditiis harum? Eaque, ullam, facere inventore assumenda excepturi aut distinctio modi voluptatum fuga accusamus pariatur ducimus similique consectetur saepe quia ad animi mollitia? Iusto, blanditiis, repudiandae, nostrum, totam ipsam corrupti fuga adipisci quibusdam soluta necessitatibus accusamus magni aliquid pariatur sapiente excepturi inventore molestiae quaerat fugit recusandae quasi impedit distinctio ut accusantium similique quidem ea optio aspernatur voluptatibus exercitationem nobis odio quisquam dicta laboriosam. Blanditiis.'
		this.phpDoc = Text.of([
			`<?php`,
			``,
			`namespace App\\Service;`,
			``,
			`/**`,
			` * ${phpDocMainComment}`,
			` */`,
			`class SomeBaseClass {`,
			`\t` + `public static function setter(): static {`,
			`\t\t` + `return $this;`,
			`\t` + `}`,
			``,
			`\t` + `public static function setter2(): static {`,
			`\t\t` + `return $this;`,
			`\t` + `}`,
			``,
			`\t` + `public static function setter3(): static {`,
			`\t\t` + `return $this;`,
			`\t` + `}`,
			`}`,
		])
		
		this.javascriptDoc = Text.of([
			`import { Controller } from '@hotwired/stimulus'`,
			``,
			`export default class extends Controller {`,
			`\t` + `connect() {`,
			`\t\t` + `console.log('Stimulus controller is working...');`,
			`\t` + `}`,
			`}`,
		])
		
		const highlightStyle = HighlightStyle.define([
			/*
			{
				tag: tags.keyword,
				color: "red",
			},
			*/
			{
				tag: tags.comment,
				color: "gray",
				fontStyle: "italic"
			},
		])
		
		const state = EditorState.create({
			doc: this.phpDoc,
			//selection: EditorSelection.create(),
			extensions: [
				basicSetup,
				
				// Dynamic
				this.language.of(php()),
				this.tabSize.of(EditorState.tabSize.of(4)),
				
				// Listeners
				EditorState.transactionExtender.of(this.editorViewStateChanged.bind(this)),
				
				//syntaxHighlighting(highlightStyle),
				keymap.of([
					indentWithTab,
					{
						key: "Mod-h",
						preventDefault: true,
						run: this.underlineSelection.bind(this),
					},
				]),
				
				EditorView.theme({
					"&": {
						color: "#000000",
						backgroundColor: "#f2f2f2",
					},
					"&.cm-editor": {
						height: "200px",
					},
					"&.cm-editor .cm-content": {
						fontFamily: "cmd",
					},
					".cm-activeLine": {
						color: "#385170",
						backgroundColor: '#a2a8d363',
					},
					/*
					*/
					"&.cm-focused .cm-selectionBackground, ::selection": {
						backgroundColor: '#a2a8d3 !important',
					},
					".cm-scroller": {
						overflow: "auto",
					},
					"&.cm-focused .cm-cursor": {
						borderLeftColor: "#393e46"
					},
					".cm-gutters": {
						backgroundColor: "#a2a8d3",
						color: "#e7eaf6",
						/*
						borderLeftColor: "#113f67",
						borderLeftStyle: "solid",
						borderLeftWidth: "2px",
						*/
					},
					".cm-gutters .cm-activeLineGutter": {
						color: "#113f67",
					},
					".cm-selectionMatch": {
						backgroundColor: '#a2a8d3',
					},
					"& .cm-line ::selection": {
						backgroundColor: 'red',
					},
					".cm-o-replacement": {
						display: "inline-block",
						width: ".5em",
						height: ".5em",
						borderRadius: ".25em"
					},
					/*
					"&.cm-focused .cm-selectionBackground, ::selection": {
						backgroundColor: 'red',
					},
					*/
				}, {dark: false}),
				keymap.of(defaultKeymap),
				
				EditorState.allowMultipleSelections.of(true),
			],
		})
		
		this.editorView = new EditorView({
			state,
			parent: this.editor,
		})
	}
	
	/**
     * Code mirror listener
     */
	editorViewStateChanged(transaction) {
		if (!transaction.docChanged) {
			return null
		}
		//console.log(transaction.newDoc.text)
		return null
		return {
			effects: this.language.reconfigure(html()),
		}
	}
	
	/**
     * Code mirror keymap
     */
	underlineSelection(editorView) {
		const addUnderline = StateEffect.define({
			map: ({from, to}, change) => ({from: change.mapPos(from), to: change.mapPos(to)})
		})
		
		const underlineField = StateField.define({
			create() {
				return Decoration.none
			},
			update(underlines, tr) {
				underlines = underlines.map(tr.changes)
				for (let e of tr.effects) if (e.is(addUnderline)) {
				underlines = underlines.update({
					add: [underlineMark.range(e.value.from, e.value.to)]
				})
				}
			return underlines
		  },
		  provide: f => EditorView.decorations.from(f),
		})
		
		const underlineMark = Decoration.mark({class: "cm-underline"})
		
		const underlineTheme = EditorView.baseTheme({
			".cm-underline": {
				textDecoration: "underline 1px black",
			}
		})
		
		const effects = editorView.state.selection.ranges
			.filter(r => !r.empty)
			.map(({from, to}) => addUnderline.of({from, to}))
		if (!effects.length) {
			return false
		}

		if (!editorView.state.field(underlineField, false)) {
			effects.push(StateEffect.appendConfig.of([underlineField, underlineTheme]))
		}
		editorView.dispatch({effects})
		return true
	}
	
	/**
     * Target getter
     */
	get editor() {
		return this.hasEditorTarget ? this.editorTarget : undefined
	}
	
	/**
     * Action
     */
	setJavascriptLanguage(event) {
		this.clearEditorView()
		this.editorView.dispatch({
			changes: {
				from: 0,
				insert: this.javascriptDoc,
			},
			effects: [
				this.language.reconfigure(javascript()),
				this.tabSize.reconfigure(EditorState.tabSize.of(2)),
			],
		})
	}
	
	/**
     * Action
     */
	setDefaultCursorPosition(event) {
		this.editorView.dispatch({
			selection: EditorSelection.create([
				EditorSelection.range(4, 5),
				EditorSelection.range(16, 27),
				EditorSelection.cursor(8),
			], 1),
		})
	}
	
	/**
     * Action
     */
	setPhpLanguage(event) {
		this.clearEditorView()
		this.editorView.dispatch({
			changes: {
				from: 0,
				insert: this.phpDoc,
			},
			effects: [
				this.language.reconfigure(php()),
				this.tabSize.reconfigure(EditorState.tabSize.of(4)),
			],
		})
	}
	
	/**
     * Action
     */
	setSimpleExample(event) {
		this.clearEditorView()
		this.editorView.dispatch({
			changes: {
				from: 0,
				insert: Text.of(['1', '2', '3']),
			},
		})
	}
	
	/**
     * API
     */
	clearEditorView() {
		this.editorView.dispatch({
			changes: {
				from: 0,
				to: this.editorView.state.doc.length,
			},
		})
	}
}
