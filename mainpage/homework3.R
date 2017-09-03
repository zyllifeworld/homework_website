library("RNAseqData.HNRNPC.bam.chr14")
indir <- system.file("extdata", package="RNAseqData.HNRNPC.bam.chr14", mustWork=TRUE)
list.files(indir)
csvfile <- file.path(indir, "sample_table.csv")
sampleTable <- read.csv(csvfile, row.names = 1)
filenames <- file.path(indir, paste0(sampleTable$Run, "_chr14.bam"))
file.exists(filenames)
library("Rsamtools")
bamfiles <- BamFileList(filenames, yieldSize=2000000)
library("GenomicFeatures")
gtffile <- file.path(indir,"human_chr14_NCBI.gtf")
txdb <- makeTxDbFromGFF(gtffile, format = "gtf", circ_seqs = character())
ebg <- exonsBy(txdb, by="gene")
library("GenomicAlignments")
library("BiocParallel")
register(SerialParam())
se <- summarizeOverlaps(features=ebg, reads=bamfiles,
                        mode="Union",
                        singleEnd=FALSE,
                        ignore.strand=TRUE,
                        fragments=TRUE )
colData(se) <- DataFrame(sampleTable)
library("DESeq2")
dds <- DESeqDataSet(se, design = ~LibraryName)
countdata <- assay(se)
head(countdata, 3)
coldata <- colData(se)
ddsMat <- DESeqDataSetFromMatrix(countData = countdata,
                                 colData = coldata,
                                 design = ~LibraryName)
dds <- dds[ rowSums(counts(dds)) > 1, ]
rld <- rlog(dds, blind = FALSE)
#vsd <- vst(dds, blind = FALSE)
library("dplyr")
library("ggplot2")

dds <- estimateSizeFactors(dds)

df <- bind_rows(
  as_data_frame(log2(counts(dds, normalized=TRUE)[, 1:2]+1)) %>%
    mutate(transformation = "log2(x + 1)"),
  as_data_frame(assay(rld)[, 1:2]) %>% mutate(transformation = "rlog"))

colnames(df)[1:2] <- c("x", "y")  

ggplot(df, aes(x = x, y = y)) + geom_hex(bins = 80) +
  coord_fixed() + facet_grid( . ~ transformation)
sampleDists <- dist(t(assay(rld)))
library("pheatmap")
library("RColorBrewer")
sampleDistMatrix <- as.matrix( sampleDists )
rownames(sampleDistMatrix) <- paste( rld$dex, rld$cell, sep = " - " )
colnames(sampleDistMatrix) <- NULL
colors <- colorRampPalette( rev(brewer.pal(9, "Blues")) )(255)
pheatmap(sampleDistMatrix,
         clustering_distance_rows = sampleDists,
         clustering_distance_cols = sampleDists,
         col = colors)
plotPCA(rld, intgroup = "LibraryName")
dds <- DESeq(dds)
res <- results(dds)
mcols(res, use.names = TRUE)
sum(res$pvalue < 0.05, na.rm=TRUE)
sum(!is.na(res$pvalue))
resSig <- subset(res, padj < 0.1)
head(resSig[ order(resSig$log2FoldChange), ])
head(resSig[ order(resSig$log2FoldChange, decreasing = TRUE), ])
topGene <- rownames(res)[which.min(res$padj)]
plotCounts(dds, gene = topGene, intgroup=c("LibraryName"))
#this part for Control and HNRNPC-KD1
res <- lfcShrink(dds, contrast=c("LibraryName","HNRNPC-KD1","Control"), res=res)
plotMA(res, ylim = c(-2, 2))

plotMA(res, ylim = c(-5,5))
topGene <- rownames(res)[which.min(res$padj)]
with(res[topGene, ], {
  points(baseMean, log2FoldChange, col="dodgerblue", cex=2, lwd=2)
  text(baseMean, log2FoldChange, topGene, pos=2, col="dodgerblue")
})
#this part for Control and HNRNPC-KD2
res <- lfcShrink(dds, contrast=c("LibraryName","HNRNPC-KD1","Control"), res=res)
plotMA(res, ylim = c(-2, 2))


plotMA(res, ylim = c(-5,5))
topGene <- rownames(res)[which.min(res$padj)]
with(res[topGene, ], {
  points(baseMean, log2FoldChange, col="dodgerblue", cex=2, lwd=2)
  text(baseMean, log2FoldChange, topGene, pos=2, col="dodgerblue")
})


hist(res$pvalue[res$baseMean > 1], breaks = 0:20/20,
     col = "grey50", border = "white")

library("genefilter")
topVarGenes <- head(order(rowVars(assay(rld)), decreasing = TRUE), 20)

mat  <- assay(rld)[ topVarGenes, ]
mat  <- mat - rowMeans(mat)
anno <- as.data.frame(colData(rld)[, c("LibraryName")])
rownames(anno) <- colnames(mat)
pheatmap(mat, annotation_col = anno)
